<?php

namespace App\Services\Media;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\UploadedFile;

/**
 * Wraps all Cloudinary SDK calls.
 *
 * Config needed in .env:
 *   CLOUDINARY_CLOUD_NAME=your_cloud_name
 *   CLOUDINARY_API_KEY=your_api_key
 *   CLOUDINARY_API_SECRET=your_api_secret
 */
class CloudinaryService
{
    private Cloudinary $cloudinary;

    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key'    => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => true,
            ],
        ]);

        $this->cloudinary = new Cloudinary();
    }

    /**
     * Upload an image file to Cloudinary.
     *
     * Returns: { url, cloudinary_id }
     */
    public function uploadImage(UploadedFile $file, string $folder): array
    {
        $result = $this->cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder'         => $folder,
                'resource_type'  => 'image',
                'transformation' => [
                    // Auto-format (WebP for browsers that support it)
                    // Auto-quality (Cloudinary picks the best quality/size tradeoff)
                    ['fetch_format' => 'auto', 'quality' => 'auto'],
                ],
            ]
        );

        return [
            'url'           => (string) $result['secure_url'],
            'cloudinary_id' => (string) $result['public_id'],
        ];
    }

    /**
     * Upload a video file to Cloudinary.
     *
     * Returns: { url, cloudinary_id }
     */
    public function uploadVideo(UploadedFile $file, string $folder): array
    {
        $result = $this->cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder'        => $folder,
                'resource_type' => 'video',
            ]
        );

        return [
            'url'           => (string) $result['secure_url'],
            'cloudinary_id' => (string) $result['public_id'],
        ];
    }

    /**
     * Delete a file from Cloudinary by its public_id.
     * Safe to call with null (no-op for Unsplash/YouTube/Instagram).
     */
    public function delete(?string $cloudinaryId, string $resourceType = 'image'): void
    {
        if (!$cloudinaryId) return;

        $this->cloudinary->uploadApi()->destroy(
            $cloudinaryId,
            ['resource_type' => $resourceType]
        );
    }
}