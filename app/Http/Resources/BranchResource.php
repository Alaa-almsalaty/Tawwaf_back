<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'phone' => $this->phone,
            'email' => $this->email,
            'capacity' => $this->capacity,
            'active' => $this->active,
            'note' => $this->note,
            //'tenant_id' => new TenantResource($this->whenLoaded('tenant')),
            'tenant_id' => $this->tenant_id,
            'tenant' => $this->whenLoaded('tenant', function () {
            return [
                'company_name' => $this->tenant->company_name,
            ];
        }),
            'manager_name' => $this->manager_name
        ];
    }
}
