<?php

namespace App\Http\Controllers\Api\Owner;

use App\Helpers\ApiResponse;
use App\Models\Gym;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    /**
     * GET /api/owner/gym/qr-code
     * Downloadable QR — the printed poster travelers scan to self check-in.
     *
     * SVG, not PNG: simple-qrcode's PNG backend needs the imagick PHP
     * extension, which isn't installed here (and often isn't on shared
     * hosting either). SVG needs no extension, and is actually the better
     * choice for a printed poster anyway — vector, so it never pixelates
     * no matter how large it's printed.
     */
    public function show(Request $request): Response|JsonResponse
    {
        $gym = $request->user()->gym;
        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found.');
        }

        if (!$gym->qr_token) {
            $gym->update(['qr_token' => $this->generateToken()]);
        }

        $checkInUrl = rtrim(config('app.frontend_url'), '/') . '/gyms/check-in/' . $gym->qr_token;
        $svg = QrCode::format('svg')->size(600)->margin(2)->generate($checkInUrl);

        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . Str::slug($gym->name) . '-checkin-qr.svg"');
    }

    /**
     * POST /api/owner/gym/qr-code/regenerate
     * Kills the old printed poster immediately — every future scan of it
     * will fail the token lookup in CheckInController.
     */
    public function regenerate(Request $request): JsonResponse
    {
        $gym = $request->user()->gym;
        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found.');
        }

        $gym->update(['qr_token' => $this->generateToken()]);

        return ApiResponse::ok('qr_regenerated', 'QR code regenerated. The old printed poster will no longer work.');
    }

    /**
     * GET /api/owner/gym/check-ins
     * Recent check-in log for this gym.
     */
    public function checkIns(Request $request): JsonResponse
    {
        $gym = $request->user()->gym;
        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found.');
        }

        $checkIns = $gym->checkIns()
            ->with('user')
            ->latest('scanned_at')
            ->take(50)
            ->get()
            ->map(fn ($c) => [
                'id'          => $c->id,
                'user_name'   => $c->user->name ?? 'Unknown traveler',
                'scanned_at'  => $c->scanned_at->format('d M Y, h:i A'),
            ]);

        return ApiResponse::ok('check_ins_fetched', 'Check-ins fetched.', ['check_ins' => $checkIns]);
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function generateToken(): string
    {
        do {
            $token = Str::random(32);
        } while (Gym::where('qr_token', $token)->exists());

        return $token;
    }
}
