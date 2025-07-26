<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;

class RegisterRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {

        return [
            'username' => 'required|string|max:40|unique:users|regex:/^(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'string', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,20}$/', 'max:20'],
            'full_name' => 'required|string|min:8|max:20',
            'phone' => 'required|starts_with:09|digits:10',
            'is_Active' => 'boolean',
            'role' => ['required', Rule::enum(UserRole::class)],
            'tenant_id' => 'sometimes|exists:tenants,id',
        ];

    }

    public function CreateUser(): array
    {
        return [
            'username' => $this->validated('username'),
            'email' => $this->validated('email'),
            'full_name' => $this->validated('full_name'),
            'password' => Hash::make($this->validated('password')),
            'phone' => $this->validated('phone'),
            'is_Active' => $this->validated('is_Active') ?? false,
            'role' => $this->validated('role'),
            'tenant_id' => $this->validated('tenant_id') ?? null,
        ];
    }

}
