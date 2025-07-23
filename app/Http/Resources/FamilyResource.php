<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FamilyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            'family_id'=> $this->id,
            'family_master_id' => new ClientResource($this->whenLoaded('master')),
            'family_name_ar' => $this->family_name_ar,
            'family_name_en' => $this->family_name_en,
            'family_size' => $this->family_size,
            'note' => $this->note,
        ];
    }
}
