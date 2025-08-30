<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'visitor_id' => UserResource::make($this->whenLoaded('visitor')),
            'package_id' => PackageResource::make($this->whenLoaded('package')),
            'extra_services' => $this->extra_services,
            'has_transportation' => $this->has_transportation,
            'has_ticket' => $this->has_ticket,
            'number_of_travelers' => $this->number_of_travelers,
            'created_by' => $this->created_by,
            'reservation_date' => $this->reservation_date,
            'reservation_state' => $this->reservation_state,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
