<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBranchRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'email' => 'nullable|email',
            'manager_name' => 'nullable|string|max:100',
            'capacity' => 'nullable|integer',
            'phone' => 'nullable|string|max:20',
            'note' => 'nullable|string',
            'active' => 'sometimes|boolean',
            'tenant_id' => 'required|exists:tenants,id',
        ];
    }

    public function CreateBranchRequest(): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'email' => $this->email,
            'manager_name' => $this->manager_name ?? null,
            'capacity' => $this->capacity ?? null,
            'phone' => $this->phone,
            'note' => $this->note ?? null,
            'tenant_id' => $this->tenant_id,
        ];
    }
}
