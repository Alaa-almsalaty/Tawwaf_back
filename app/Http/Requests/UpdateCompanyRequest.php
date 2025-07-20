<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->user()->isSuperAdmin();
    }


    public function rules(): array
    {
        return [
            'company_name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:tenants,email',
            'status' => 'sometimes|in:active,inactive,trial,free',
            'balance' => 'sometimes|numeric|min:0',
            'manager_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'note' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'data' => 'nullable|array',
            'created_by' => 'sometimes|exists:users,id',
        ];
    }

        public function UpdateCompany(): array
    {
        return collect($this->all())
            ->filter(fn($value, $key) => $this->has($key) && $value !== null)
            ->toArray();
    }
}
