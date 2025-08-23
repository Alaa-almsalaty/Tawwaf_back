<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'package_name' => $this->package_name,
            'package_type' => $this->package_type,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total_price_dinar' => $this->total_price_dinar,
            'total_price_usd' => $this->total_price_usd,
            'currency' => $this->currency,
            'season' => $this->season,
            'status' => auth()->user() ? $this->status : null, // Hide status for unauthenticated users
            'features' => $this->features,
            'note' => $this->note,
            'tenant_id' => new TenantResource($this->whenLoaded('tenant')),
            'MKduration' => $this->MKduration,
            'MDduration' => $this->MDduration,
            'MKHotel' => new HotelResource($this->whenLoaded('MKHotel')),
            'MDHotel' => new HotelResource($this->whenLoaded('MDHotel')),
        ];
    }
}
