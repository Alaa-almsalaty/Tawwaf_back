<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageRoomResource extends JsonResource
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
            'room_type' => $this->room_type,
            'total_price_dinar' => $this->total_price_dinar,
            'total_price_usd' => $this->total_price_usd,
        ];
    }
}
