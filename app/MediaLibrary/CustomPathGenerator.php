<?php

namespace App\MediaLibrary;

use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Client;

class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $model = $media->model;
        $collection = $media->collection_name;

        // Client collections (tenant-specific)
        if ($model instanceof Client) {
            $tenantId = $model->tenant_id ?? 'default';

            if ($collection === 'passport_images') {
                return "PassportsImages/{$tenantId}/";
            }

            if ($collection === 'personal_images') {
                return "PersonalImages/{$tenantId}/";
            }
        }

        // Tenant logos
        if ($model instanceof \App\Models\Tenant && $collection === 'logos') {
            return "Logos/";
        }

        // Package images
        if ($model instanceof \App\Models\Package && $collection === 'images') {
            return "Packages/";
        }

        return "media/{$collection}/{$model->getKey()}/";
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'responsive/';
    }
}
