<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\UserRole;

class UpdateUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('update users');
    }

    public function rules(): array
    {

        return [
            'username' => "sometimes|string|max:40",
            'password' => 'nullable|string|min:6',
            'email' => 'sometimes|email',
            'full_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'role' => [
                'sometimes',
                Rule::enum(UserRole::class)
            ],
            'is_Active' => 'boolean',
            'tenant_id' => 'nullable|exists:tenants,id',
        ];
    }

    public function updateUser(): array
    {
        return collect($this->validated())
            ->filter(fn($value, $key) => $this->has($key))
            ->toArray();
    }
    public function messages(): array
    {
        return [
            'username.required' => 'حقل اسم المستخدم مطلوب.',
            'username.string' => 'اسم المستخدم يجب أن يكون نصاً.',
            'username.max' => 'اسم المستخدم لا يجب أن يزيد عن 40 حرفاً.',

            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف.',

            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',

            'full_name.required' => 'حقل الاسم الكامل مطلوب.',
            'full_name.string' => 'الاسم الكامل يجب أن يكون نصاً.',
            'full_name.max' => 'الاسم الكامل لا يجب أن يزيد عن 255 حرفاً.',

            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.string' => 'رقم الهاتف يجب أن يكون نصاً.',
            'phone.max' => 'رقم الهاتف لا يجب أن يزيد عن 20 حرفاً.',



        ];
    }
}
