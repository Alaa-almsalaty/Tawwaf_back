<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'username' => $this->username,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'city' => $this->city,
            'phone' => $this->phone,
            'email' => $this->email,
            'role' => $this->role,
            'created_at' => $this->created_at,
            'is_Active' => $this->is_Active,
            'tenant_id' => $this->tenant_id,
            'tenant' => $this->whenLoaded('tenant', function () {
            return [
                'company_name' => $this->tenant->company_name,
                'logo' => $this->tenant->getFirstMediaUrl('logos', 'thumb'),
                'address' => $this->tenant->address,
                'city' => $this->tenant->city,
                'email' => $this->tenant->email,
                'phone' => $this->tenant->phone,
            ];
        }),

            //'tenant_id' => new TenantResource($this->whenLoaded('tenant')),

        ];
    }
}
