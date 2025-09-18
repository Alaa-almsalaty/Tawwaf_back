<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeClientsCountResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
                    'id' => $this->id,
                    'full_name' => $this->full_name,
                    'clients_count' => $this->clients_count,
                ];
    }
}
