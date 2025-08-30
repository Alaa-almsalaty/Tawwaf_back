<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCartRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'visitor' => 'required|exists:users,id',
            'package' => 'required|exists:packages,id',
        ];
    }

    public function createCart(): array
    {
        return [
            'visitor' => $this->visitor,
            'package' => $this->package,
        ];
    }
}
