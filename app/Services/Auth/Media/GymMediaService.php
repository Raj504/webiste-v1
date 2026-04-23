<?php

namespace App\Services\Media;

use App\Models\Gym;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class GymMediaService
{
    private $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // COVER PHOTO
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Upload a cover photo from the owner's device.
     * Deletes the old cover from Cloudinary if it was an upload.
     */
    public function uploadCover(Gym $gym, UploadedFile $file): array
    {
        // Delete old cover from Cloudinary if it was an uploaded file
        $this->deleteOldCover($gym);

        $folder = "gympass/gyms/{$gym->id}/cover";
        $result = $this->cloudinary->uploadImage($file, $folder);

        $cover = [
            'url'           => $result['url'],
            'cloudinary_id' => $result['cloudinary_id'],
            'source'        => 'upload',
        ];

        $gym->update(['cover_photo' => $cover]);

        return $cover;
    }

    /**
     * Set a cover photo from Unsplash.
     * Just stores the URL — nothing uploaded to Cloudinary.
     */
    public function setCoverFromUnsplash(Gym $gym, string $url, string $photographerName): array
    {
        // Delete old cover from Cloudinary if it was an uploaded file
        $this->deleteOldCover($gym);

        $cover = [
            'url'                => $url,
            'cloudinary_id'      => null,
            'source'             => 'unsplash',
            'photographer_name'  => $photographerName,
        ];

        $gym->update(['cover_photo' => $cover]);

        return $cover;
    }

    /**
     * Remove the cover photo entirely.
     */
    public function removeCover(Gym $gym): void
    {
        $this->deleteOldCover($gym);
        $gym->update(['cover_photo' => null]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GYM PHOTOS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Upload one or more photos from device. Max 10 total per gym.
     *
     * @param  UploadedFile[]  $files
     * @return array  newly added photos
     * @throws \RuntimeException if limit exceeded
     */
    public function uploadPhotos(Gym $gym, array $files): array
    {
        $existing = $gym->photos ?? [];

        if (count($existing) + count($files) > 10) {
            throw new \RuntimeException(
                'Photo limit reached. A gym can have a maximum of 10 photos.'
            );
        }

        $folder    = "gympass/gyms/{$gym->id}/photos";
        $nextOrder = count($existing) + 1;
        $added     = [];

        foreach ($files as $file) {
            $result  = $this->cloudinary->uploadImage($file, $folder);
            $added[] = [
                'id'            => (string) Str::uuid(),
                'url'           => $result['url'],
                'cloudinary_id' => $result['cloudinary_id'],
                'source'        => 'upload',
                'sort_order'    => $nextOrder++,
            ];
        }

        $gym->update(['photos' => array_merge($existing, $added)]);

        return $added;
    }

    /**
     * Add a photo from Unsplash (no Cloudinary upload).
     *
     * @throws \RuntimeException if limit exceeded
     */
    public function addPhotoFromUnsplash(Gym $gym, string $url, string $photographerName): array
    {
        $existing = $gym->photos ?? [];

        if (count($existing) >= 10) {
            throw new \RuntimeException(
                'Photo limit reached. A gym can have a maximum of 10 photos.'
            );
        }

        $photo = [
            'id'               => (string) Str::uuid(),
            'url'              => $url,
            'cloudinary_id'    => null,
            'source'           => 'unsplash',
            'photographer_name'=> $photographerName,
            'sort_order'       => count($existing) + 1,
        ];

        $gym->update(['photos' => array_merge($existing, [$photo])]);

        return $photo;
    }

    /**
     * Delete a single gym photo by its UUID.
     * Removes from Cloudinary if it was uploaded.
     *
     * @throws \RuntimeException if photo not found
     */
    public function deletePhoto(Gym $gym, string $photoId): void
    {
        $photos = $gym->photos ?? [];
        $target = collect($photos)->firstWhere('id', $photoId);

        if (!$target) {
            throw new \RuntimeException('Photo not found.');
        }

        // Only delete from Cloudinary if it was uploaded (not Unsplash)
        if ($target['source'] === 'upload') {
            $this->cloudinary->delete($target['cloudinary_id'] ?? null);
        }

        // Remove from array and re-index sort_order
        $remaining = collect($photos)
            ->reject(fn($p) => $p['id'] === $photoId)
            ->values()
            ->map(function ($photo, $index) {
                $photo['sort_order'] = $index + 1;
                return $photo;
            })
            ->toArray();

        $gym->update(['photos' => $remaining]);
    }

    /**
     * Reorder gym photos.
     *
     * @param  array  $orderedIds  photo UUIDs in the desired order
     * @throws \RuntimeException if IDs don't match existing photos
     */
    public function reorderPhotos(Gym $gym, array $orderedIds): array
    {
        $photos    = collect($gym->photos ?? []);
        $reordered = [];

        foreach ($orderedIds as $index => $id) {
            $photo = $photos->firstWhere('id', $id);
            if (!$photo) {
                throw new \RuntimeException("Photo ID {$id} not found.");
            }
            $photo['sort_order'] = $index + 1;
            $reordered[]         = $photo;
        }

        $gym->update(['photos' => $reordered]);

        return $reordered;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // VIDEOS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Save a YouTube or Instagram Reels URL.
     * source: 'youtube' | 'instagram'
     */
    public function saveVideoUrl(Gym $gym, string $url, string $source): array
    {
        $videos          = $gym->videos ?? [];
        $videos[$source] = $url;

        $gym->update(['videos' => $videos]);

        return $videos;
    }

    /**
     * Upload a video file to Cloudinary.
     * Replaces any existing uploaded video.
     */
    public function uploadVideo(Gym $gym, UploadedFile $file): array
    {
        $videos = $gym->videos ?? [];

        // Delete old uploaded video from Cloudinary if exists
        if (!empty($videos['upload']['cloudinary_id'])) {
            $this->cloudinary->delete($videos['upload']['cloudinary_id'], 'video');
        }

        $folder = "gympass/gyms/{$gym->id}/videos";
        $result = $this->cloudinary->uploadVideo($file, $folder);

        $videos['upload'] = [
            'url'           => $result['url'],
            'cloudinary_id' => $result['cloudinary_id'],
        ];

        $gym->update(['videos' => $videos]);

        return $videos;
    }

    /**
     * Remove a specific video by source.
     * source: 'youtube' | 'instagram' | 'upload'
     */
    public function removeVideo(Gym $gym, string $source): void
    {
        $videos = $gym->videos ?? [];

        if ($source === 'upload' && !empty($videos['upload']['cloudinary_id'])) {
            $this->cloudinary->delete($videos['upload']['cloudinary_id'], 'video');
        }

        unset($videos[$source]);
        $gym->update(['videos' => $videos]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function deleteOldCover(Gym $gym): void
    {
        $old = $gym->cover_photo;
        if ($old && $old['source'] === 'upload' && !empty($old['cloudinary_id'])) {
            $this->cloudinary->delete($old['cloudinary_id']);
        }
    }
}