<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
