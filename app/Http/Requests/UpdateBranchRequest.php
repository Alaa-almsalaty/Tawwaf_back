<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'manager_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'capacity' => 'nullable|integer',
            'note' => 'nullable|string',
            'active' => 'sometimes|boolean',
            'tenant_id' => 'sometimes|exists:tenants,id'
        ];
    }

      public function UpdateBranch(): array
    {
        return collect($this->validated())
            ->filter(fn($value, $key) => $this->has($key) && $value !== null)
            ->toArray();
    }
}
