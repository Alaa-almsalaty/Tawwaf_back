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
            'visitor_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            'package_room_id' => 'required|exists:package_rooms,id',
        ];
    }

    public function createCart(): array
    {
        return [
            'visitor_id' => $this->visitor_id,
            'package_id' => $this->package_id,
            'package_room_id' => $this->package_room_id,
        ];
    }
}
