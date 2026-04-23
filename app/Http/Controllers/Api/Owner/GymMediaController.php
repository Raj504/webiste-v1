<?php

namespace App\Http\Controllers\Api\Owner;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\ReorderPhotosRequest;
use App\Http\Requests\Owner\SaveVideoUrlRequest;
use App\Http\Requests\Owner\UnsplashCoverRequest;
use App\Http\Requests\Owner\UnsplashPhotoRequest;
use App\Http\Requests\Owner\UploadCoverRequest;
use App\Http\Requests\Owner\UploadPhotosRequest;
use App\Http\Requests\Owner\UploadVideoRequest;
use App\Services\Media\GymMediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GymMediaController extends Controller
{
    private $mediaService;

    public function __construct(GymMediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // COVER PHOTO
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * POST /api/owner/gym/cover/upload
     * Upload cover photo from device.
     * Multipart: photo (image file)
     */
    public function uploadCover(UploadCoverRequest $request): JsonResponse
    {
        $gym = $request->user()->gym;

        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found for this owner.');
        }

        try {
            $cover = $this->mediaService->uploadCover($gym, $request->file('photo'));
        } catch (\Exception $e) {
            report($e);
            return ApiResponse::serverError('Failed to upload cover photo. Please try again.');
        }

        return ApiResponse::ok(
            'cover_uploaded',
            'Cover photo updated successfully.',
            ['cover_photo' => $cover]
        );
    }

    /**
     * POST /api/owner/gym/cover/unsplash
     * Set cover photo from Unsplash (no file upload).
     * Body: { url, photographer_name }
     */
    public function setCoverFromUnsplash(UnsplashCoverRequest $request): JsonResponse
    {
        $gym = $request->user()->gym;

        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found for this owner.');
        }

        $cover = $this->mediaService->setCoverFromUnsplash(
            $gym,
            $request->url,
            $request->photographer_name
        );

        return ApiResponse::ok(
            'cover_updated',
            'Cover photo updated successfully.',
            ['cover_photo' => $cover],
        );
    }

    /**
     * DELETE /api/owner/gym/cover
     * Remove cover photo.
     */
    public function removeCover(Request $request): JsonResponse
    {
        $gym = $request->user()->gym;

        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found for this owner.');
        }

        $this->mediaService->removeCover($gym);

        return ApiResponse::ok(
            'cover_removed',
            'Cover photo removed.',
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GYM PHOTOS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * GET /api/owner/gym/photos
     * List all gym photos.
     */
    public function listPhotos(Request $request): JsonResponse
    {
        $gym = $request->user()->gym;

        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found for this owner.');
        }

        return ApiResponse::ok(
            'photos_fetched',
            'Gym photos fetched.',
            [
                'photos' => $gym->photos ?? [],
                'count'  => $gym->photo_count,
                'limit'  => 10,
            ],
        );
    }

    /**
     * POST /api/owner/gym/photos/upload
     * Upload one or more photos from device.
     * Multipart: photos[] (image files)
     */
    public function uploadPhotos(UploadPhotosRequest $request): JsonResponse
    {
        $gym = $request->user()->gym;

        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found for this owner.');
        }

        try {
            $added = $this->mediaService->uploadPhotos($gym, $request->file('photos'));
        } catch (\RuntimeException $e) {
            return ApiResponse::badRequest('photo_limit_reached', $e->getMessage());
        } catch (\Exception $e) {
            report($e);
            return ApiResponse::serverError('Failed to upload photos. Please try again.');
        }

        return ApiResponse::created(
            'photos_uploaded',
            count($added) . ' photo(s) uploaded successfully.',
            [
                'added'  => $added,
                'photos' => $gym->fresh()->photos,
                'count'  => $gym->fresh()->photo_count,
            ],
        );
    }

    /**
     * POST /api/owner/gym/photos/unsplash
     * Add a photo from Unsplash.
     * Body: { url, photographer_name }
     */
    public function addPhotoFromUnsplash(UnsplashPhotoRequest $request): JsonResponse
    {
        $gym = $request->user()->gym;

        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found for this owner.');
        }

        try {
            $photo = $this->mediaService->addPhotoFromUnsplash(
                $gym,
                $request->url,
                $request->photographer_name,
            );
        } catch (\RuntimeException $e) {
            return ApiResponse::badRequest('photo_limit_reached', $e->getMessage());
        }

        return ApiResponse::created(
            'photo_added',
            'Photo added successfully.',
            [
                'photo'  => $photo,
                'photos' => $gym->fresh()->photos,
                'count'  => $gym->fresh()->photo_count,
            ],
        );
    }

    /**
     * DELETE /api/owner/gym/photos/{photoId}
     * Delete a single photo by UUID.
     */
    public function deletePhoto(Request $request, string $photoId): JsonResponse
    {
        $gym = $request->user()->gym;

        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found for this owner.');
        }

        try {
            $this->mediaService->deletePhoto($gym, $photoId);
        } catch (\RuntimeException $e) {
            return ApiResponse::badRequest('photo_not_found', $e->getMessage());
        } catch (\Exception $e) {
            report($e);
            return ApiResponse::serverError('Failed to delete photo. Please try again.');
        }

        return ApiResponse::ok(
            'photo_deleted',
            'Photo deleted successfully.',
            [
                'photos' => $gym->fresh()->photos,
                'count'  => $gym->fresh()->photo_count,
            ],
        );
    }

    /**
     * PUT /api/owner/gym/photos/reorder
     * Save new photo order.
     * Body: { ids: [uuid, uuid, ...] }
     */
    public function reorderPhotos(ReorderPhotosRequest $request): JsonResponse
    {
        $gym = $request->user()->gym;

        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found for this owner.');
        }

        try {
            $reordered = $this->mediaService->reorderPhotos($gym, $request->ids);
        } catch (\RuntimeException $e) {
            return ApiResponse::badRequest('reorder_failed', $e->getMessage());
        }

        return ApiResponse::ok(
            'photos_reordered',
            'Photo order saved.',
            ['photos' => $reordered],
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // VIDEOS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * POST /api/owner/gym/videos/url
     * Save a YouTube or Instagram Reels URL.
     * Body: { url, source: 'youtube'|'instagram' }
     */
    public function saveVideoUrl(SaveVideoUrlRequest $request): JsonResponse
    {
        $gym = $request->user()->gym;

        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found for this owner.');
        }

        $videos = $this->mediaService->saveVideoUrl($gym, $request->url, $request->source);

        return ApiResponse::ok(
            'video_saved',
            'Video link saved successfully.',
            ['videos' => $videos],
        );
    }

    /**
     * POST /api/owner/gym/videos/upload
     * Upload a video file.
     * Multipart: video (video file)
     */
    public function uploadVideo(UploadVideoRequest $request): JsonResponse
    {
        $gym = $request->user()->gym;

        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found for this owner.');
        }

        try {
            $videos = $this->mediaService->uploadVideo($gym, $request->file('video'));
        } catch (\Exception $e) {
            report($e);
            return ApiResponse::serverError('Failed to upload video. Please try again.');
        }

        return ApiResponse::ok(
            'video_uploaded',
            'Video uploaded successfully.',
            ['videos' => $videos],
        );
    }

    /**
     * DELETE /api/owner/gym/videos/{source}
     * Remove a video by source: youtube | instagram | upload
     */
    public function removeVideo(Request $request, string $source): JsonResponse
    {
        if (!in_array($source, ['youtube', 'instagram', 'upload'])) {
            return ApiResponse::badRequest(
                'invalid_source',
                'Source must be youtube, instagram, or upload.'
            );
        }

        $gym = $request->user()->gym;

        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found for this owner.');
        }

        try {
            $this->mediaService->removeVideo($gym, $source);
        } catch (\Exception $e) {
            report($e);
            return ApiResponse::serverError('Failed to remove video. Please try again.');
        }

        return ApiResponse::ok(
            'video_removed',
            'Video removed successfully.',
            ['videos' => $gym->fresh()->videos],
        );
    }
}