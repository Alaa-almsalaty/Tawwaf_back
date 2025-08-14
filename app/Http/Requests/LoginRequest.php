<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'username_or_email' => 'required|string',
            'password' => 'required|string|min:8|max:20'
        ];
    }


    public function authenticate()
    {
        $username = $this->username_or_email;
        $user = User::where('email', $username)
            ->orWhere('username', $username)
            ->first();


        if (!$user || !Hash::check($this->password, $user->password)) {
            throw ValidationException::withMessages([
                $this->username_or_email => ['بيانات الدخول غير صحيحة.'],
            ]);
        } else if (!$user->is_Active) {
            throw ValidationException::withMessages([
                $this->username_or_email => ['لم يتم تفعيل حسابك بعد.'],
            ]);

        }
        return $user;
    }
    public function messages(): array
    {
        return [
            'username_or_email.required' => 'يرجى إدخال اسم المستخدم أو البريد الإلكتروني',
            'password.required' => 'يرجى إدخال كلمة المرور',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.max' => 'كلمة المرور يجب ألا تتجاوز 20 حرفًا',
        ];
    }
}

