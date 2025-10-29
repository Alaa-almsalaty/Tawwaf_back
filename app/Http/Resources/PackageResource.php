<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'package_name' => $this->package_name,
            'package_type' => $this->package_type,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'image' => $this->image,
            //'total_price_dinar' => $this->total_price_dinar,
            //'total_price_usd' => $this->total_price_usd,
            'currency' => $this->currency,
            'season' => $this->season,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => auth()->user() ? $this->status : null,
            'features' => $this->features,
            'note' => $this->note,
            //'tenant_id' => new TenantResource($this->whenLoaded('tenant')),
            'tenant_id' => $this->tenant_id,
            'tenant' => $this->whenLoaded('tenant', function () {
            return [
                'company_name' => $this->tenant->company_name,
                   ];
            }),
            'MKduration' => $this->MKduration,
            'MDduration' => $this->MDduration,


            'MK_Hotel' => $this->whenLoaded('MK_Hotel', function () {
            return [
                'id' => $this->MK_Hotel->id,
                'hotel_name' => $this->MK_Hotel->hotel_name,
                'distance_from_center' => $this->MK_Hotel->distance_from_center,
                ];
            }),
            'MD_Hotel' => $this->whenLoaded('MD_Hotel', function () {
            return [
                'id' => $this->MD_Hotel->id,
                'hotel_name' => $this->MD_Hotel->hotel_name,
                'distance_from_center' => $this->MD_Hotel->distance_from_center,
                   ];
            }),

            'rooms' => $this->whenLoaded('rooms', function () {
                return $this->rooms->map(function ($room) {
                    return [
                        'id' => $room->id,
                        'room_type' => $room->room_type,
                        'total_price_dinar' => $room->total_price_dinar,
                        'total_price_usd' => $room->total_price_usd,
                    ];
                });
            }),

             //'MKHotel' => new HotelResource($this->whenLoaded('MK_Hotel')),
             //'MDHotel' => new HotelResource($this->whenLoaded('MD_Hotel')),
        ];
    }
}
