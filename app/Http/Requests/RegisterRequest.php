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
            'full_name' => 'nullable|string|min:8|max:20',
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'phone' => 'required|starts_with:+21',
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
            'full_name' => $this->validated('full_name') ?? $this->validated('first_name') . ' ' . ($this->validated('last_name') ?? ''),
            'first_name' => $this->validated('first_name'),
            'last_name' => $this->validated('last_name') ?? null,
            'city' => $this->validated('city') ?? null,
            'password' => Hash::make($this->validated('password')),
            'phone' => $this->validated('phone'),
            'is_Active' => $this->validated('is_Active') ?? false,
            'role' => $this->validated('role'),
            'tenant_id' => $this->validated('tenant_id') ?? null,
        ];
    }
public function messages(): array
{
    return [
        'username.required' => 'حقل اسم المستخدم مطلوب.',
        'username.string' => 'اسم المستخدم يجب أن يكون نصاً.',
        'username.max' => 'اسم المستخدم لا يمكن أن يزيد عن 40 حرفاً.',
        'username.unique' => 'اسم المستخدم مستخدم مسبقاً.',
        'username.regex' => 'اسم المستخدم غير صالح. يجب أن يحتوي على حروف وأرقام ونقاط أو شرطات سفلية فقط.',

        'email.required' => 'حقل البريد الإلكتروني مطلوب.',
        'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
        'email.unique' => 'البريد الإلكتروني مستخدم مسبقاً.',

        'password.required' => 'حقل كلمة المرور مطلوب.',
        'password.string' => 'كلمة المرور يجب أن تكون نصاً.',
        'password.regex' => 'كلمة المرور يجب أن تحتوي على حروف كبيرة وصغيرة وأرقام ورموز خاصة.',
        'password.max' => 'كلمة المرور لا يمكن أن تزيد عن 20 حرفاً.',

        'full_name.required' => ' الاسم الكامل مطلوب.',
        'full_name.string' => 'الاسم الكامل يجب أن يكون نصاً.',
        'full_name.min' => 'الاسم الكامل يجب أن لا يقل عن 8 أحرف.',
        'full_name.max' => 'الاسم الكامل لا يمكن أن يزيد عن 20 حرفاً.',

        'first_name.required' => 'حقل الاسم الأول مطلوب.',
        'first_name.string' => 'الاسم الأول يجب أن يكون نصاً.',
        'first_name.max' => 'الاسم الأول لا يمكن أن يزيد عن 50 حرفاً.',

        'last_name.string' => 'اسم العائلة يجب أن يكون نصاً.',
        'last_name.max' => 'اسم العائلة لا يمكن أن يزيد عن 50 حرفاً.',

        'city.string' => 'اسم المدينة يجب أن يكون نصاً.',
        'city.max' => 'اسم المدينة لا يمكن أن يزيد عن 100 حرفاً.',

        'phone.required' => 'حقل رقم الهاتف مطلوب.',
        'phone.starts_with' => 'رقم الهاتف يجب أن يبدأ بـ 09.',
        'phone.digits' => 'رقم الهاتف يجب أن يتكون من 10 أرقام.',

        'is_Active.boolean' => 'حالة التفعيل يجب أن تكون صحيحة أو خاطئة.',

        'role.required' => 'حقل الدور مطلوب.',
        'role.enum' => 'الدور المختار غير صالح.',

        'tenant_id.exists' => 'معرف الشركة غير موجود',
    ];
}

}
