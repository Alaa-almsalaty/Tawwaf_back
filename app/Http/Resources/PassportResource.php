<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PassportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            'passport_number' => $this->passport_number,
            'passport_type' => $this->passport_type,
            'nationality' => $this->nationality,
            'issue_date' => $this->issue_date,
            'expiry_date' => $this->expiry_date,
            'issue_place' => $this->issue_place,
            'birth_place' => $this->birth_place,
            'issue_authority' => $this->issue_authority,
            'passport_img' => $this->passport_img,
        ];
    }
}
