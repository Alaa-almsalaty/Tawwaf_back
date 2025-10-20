<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hotel_name' => $this->hotel_name,
            'manager_name' => $this->manager_name,
            'address' => $this->address,
            'city' => $this->city,
            'phone' => $this->phone,
            'capacity' => $this->capacity,
            'rooms_count' => $this->rooms_count,
            'stars' => $this->stars,
            'distance_from_center' => $this->distance_from_center,
            'note' => $this->note,
            'provider_id' => $this->provider_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }

    }

