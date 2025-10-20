<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHotelRequest extends FormRequest
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
            'hotel_name' => 'sometimes|required|string|max:255|unique:hotels,hotel_name,' . $this->route('hotel'),
            'city' => 'sometimes|required|string|max:255|in:makka,medina,mena',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'manager_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'capacity' => 'sometimes|required|integer',
            'stars' => 'sometimes|required|in:one,two,three,four,five,six,seven',
            'distance_from_center' => 'sometimes|required|numeric|min:0',
            'note' => 'nullable|string',
            'provider_id' => 'nullable|exists:providers,id',
            'rooms_count' => 'sometimes|required|integer|min:1',
        ];
    }

    public function updateHotel()
    {
        return collect($this->validated())
            ->filter(fn($value, $key) => $this->has($key) && $value !== null)
            ->toArray();
    }
}
