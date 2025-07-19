<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            'first_name_ar' => $this->first_name_ar,
            'first_name_en' => $this->first_name_en,
            'second_name_ar' => $this->second_name_ar,
            'second_name_en' => $this->second_name_en,
            'grand_father_name_ar' => $this->grand_father_name_ar,
            'grand_father_name_en' => $this->grand_father_name_en,
            'last_name_ar' => $this->last_name_ar,
            'last_name_en' => $this->last_name_en,
            'DOB' => $this->DOB,
            'family_status' => $this->family_status,
            'gender' => $this->gender,
            'medical_status' => $this->medical_status,
            'phone' => $this->phone,
            'passport_no' => new PassportResource($this->whenLoaded('passport')),
        ];
    }
}
