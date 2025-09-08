<?php

namespace App\Http\Resources;

//use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'is_family_master' => $this->is_family_master,
            'register_date' => $this->register_date,
            'register_state' => $this->register_state,
            'branch' => new BranchResource($this->whenLoaded('branch')),
            'tenant_id' => new TenantResource($this->whenLoaded('tenant')),
            'personal_info' => new PersonalResource($this->whenLoaded('personalInfo')),
            'family' => new FamilyResource($this->whenLoaded('family')),
            'muhram' => new ClientResource($this->whenLoaded('muhram')),
            'Muhram_relation' => $this->Muhram_relation,
            'note' => $this->note,
            //'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'created_by' =>$this->whenLoaded('createdBy', function () {
            return [
                'id' => $this->createdBy->id,
            ];
        }),
        ];
    }
}

