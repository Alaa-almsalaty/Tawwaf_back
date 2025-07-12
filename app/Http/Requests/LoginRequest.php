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
                $this->username_or_email => ['Invalid credentials.'],
            ]);
        } else if (!$user->is_Active) {
            throw ValidationException::withMessages([
                $this->username_or_email => ['Your account is not approved yet.'],
            ]);

        }
        return $user;
    }

}

