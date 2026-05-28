<?php

namespace App\Http\Controllers\Api\Owner;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\CreateGymPlanRequest;
use App\Http\Requests\Owner\UpdateGymPlanRequest;
use App\Models\GymPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GymPlanController extends Controller
{
    /**
     * GET /api/owner/gym/plans
     * List all plans for this gym.
     */
    public function index(Request $request): JsonResponse
    {
        $gym = $request->user()->gym;
        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found.');
        }

        $plans = $gym->plans()
            ->orderBy('unit')           
            ->orderBy('duration')     
            ->get()
            ->map(fn($p) => $this->format($p));

        return ApiResponse::ok('plans_fetched', 'Plans fetched.', ['plans' => $plans]);
    }

    /**
     * POST /api/owner/gym/plans
     * Create a new custom plan.
     * Body: { duration, unit, price }
     */
    public function store(CreateGymPlanRequest $request): JsonResponse
    {
        $gym = $request->user()->gym;
        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found.');
        }

        // Prevent duplicate duration+unit combinations
        $exists = $gym->plans()
            ->where('duration', $request->duration)
            ->where('unit', $request->unit)
            ->exists();

        if ($exists) {
            return ApiResponse::badRequest(
                'plan_exists',
                "A {$request->duration} {$request->unit} plan already exists. Update its price instead."
            );
        }
        
        $plan = $gym->plans()->create([
            'name'       => GymPlan::makeName($request->duration, $request->unit),
            'duration'   => $request->duration,
            'unit'       => $request->unit,
            'price'      => $request->price,
            'is_default' => false,
            'is_enabled' => true,
        ]);
        return ApiResponse::created(
            'plan_created',
            'Plan created successfully.',
            ['plan' => $this->format($plan)]
        );
    }

    /**
     * PUT /api/owner/gym/plans/{planId}
     * Update price or enabled status of any plan.
     * Body: { price?, is_enabled? }
     */
    public function update(UpdateGymPlanRequest $request, int $planId): JsonResponse
    {
        $gym  = $request->user()->gym;
        $plan = $gym ? $gym->plans()->find($planId) : null;

        if (!$plan) {
            return ApiResponse::badRequest('plan_not_found', 'Plan not found.');
        }

        $plan->update($request->only(['price', 'is_enabled']));

        return ApiResponse::ok(
            'plan_updated',
            'Plan updated successfully.',
            ['plan' => $this->format($plan->fresh())]
        );
    }

    /**
     * DELETE /api/owner/gym/plans/{planId}
     * Delete a custom plan. Default plans cannot be deleted.
     */
    public function destroy(Request $request, int $planId): JsonResponse
    {
        $gym  = $request->user()->gym;
        $plan = $gym ? $gym->plans()->find($planId) : null;

        if (!$plan) {
            return ApiResponse::badRequest('plan_not_found', 'Plan not found.');
        }

        if ($plan->is_default) {
            return ApiResponse::badRequest(
                'plan_not_deletable',
                'Default plans cannot be deleted. You can disable them instead.'
            );
        }

        $plan->delete();

        return ApiResponse::ok('plan_deleted', 'Plan deleted successfully.');
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function format(GymPlan $plan): array
    {
        return [
            'id'         => $plan->id,
            'name'       => $plan->name,
            'label'      => $plan->display_label,
            'duration'   => $plan->duration,
            'unit'       => $plan->unit,
            'price'      => $plan->price,
            'is_default' => $plan->is_default,
            'is_enabled' => $plan->is_enabled,
        ];
    }
}