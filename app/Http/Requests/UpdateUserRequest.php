<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'username'    => "required|string|max:40",
            'password'    => 'nullable|string|min:6',
            'email' => 'sometimes|email',
            'full_name'   => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'role'        => 'required|in:employee,manager,super',
            'is_Active'   => 'boolean',
            'tenant_id'   => 'nullable|exists:tenants,id',
        ];
    }

        public function updateUser(): array
    {
        return collect($this->validated())
            ->filter(fn ($value, $key) => $this->has($key))
            ->toArray();
    }
}
