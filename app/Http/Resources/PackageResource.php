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
            'features' => $this->features,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'MKduration' => $this->MKduration,
            'MDduration' => $this->MDduration,
            'total_price_dinar' => $this->total_price_dinar,
            'total_price_usd' => $this->total_price_usd,
            'currency' => $this->currency,
            'season' => $this->season,
            'status' => $this->status,
            'note' => $this->note,
            'tenant_id' => $this->tenant_id,
            'MKHotel' => $this->whenLoaded('MKHotel'),
            'MDHotel' => $this->whenLoaded('MDHotel'), 
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        }
}
