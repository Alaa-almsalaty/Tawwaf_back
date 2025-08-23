<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateHotelRequest extends FormRequest
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
            'hotel_name' => 'required|string|max:255|unique:hotels,hotel_name',
            'city' => 'required|string|max:255|in:makka,medina,mena',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'manager_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'capacity' => 'required|integer',
            'stars' => 'required|in:one,two,three,four,five,six,seven',
            'distance_from_center' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'provider_id' => 'nullable|exists:providers,id',
            'rooms_count' => 'required|integer|min:1',
        ];
    }

    public function createHotel(): array
    {
        return [
            'hotel_name' => $this->hotel_name,
            'city' => $this->city ,
            'address' => $this->address ?? null,
            'email' => $this->email ?? null,
            'manager_name' => $this->manager_name ?? null,
            'phone' => $this->phone ?? null,
            'capacity' => $this->capacity ?? 0,
            'stars' => $this->stars,
            'distance_from_center' => $this->distance_from_center,
            'note' => $this->note ?? null,
            'provider_id' => $this->provider_id ?? null,
            'rooms_count' => $this->rooms_count ?? 1
        ];
    }

    public function messages(): array
    {
        return [
            'hotel_name.required' => 'اسم الفندق مطلوب.',
            'hotel_name.string' => 'اسم الفندق يجب أن يكون نصاً.',
            'hotel_name.max' => 'اسم الفندق لا يجب أن يزيد عن 255 حرفاً.',
            'hotel_name.unique' => 'اسم الفندق موجود بالفعل.',

            'city.required' => 'المدينة مطلوبة.',
            'city.string' => 'المدينة يجب أن تكون نصاً.',
            'city.in' => 'المدينة يجب أن تكون واحدة من: makka, medina, mena.',

            'address.string' => 'العنوان يجب أن يكون نصاً.',
            'address.max' => 'العنوان لا يجب أن يزيد عن 255 حرفاً.',

            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',

            'manager_name.string' => 'اسم المدير يجب أن يكون نصاً.',
            'manager_name.max' => 'اسم المدير لا يجب أن يزيد عن 255 حرفاً.',

            'phone.string' => 'رقم الهاتف يجب أن يكون نصاً.',
            'phone.max' => 'رقم الهاتف لا يجب أن يزيد عن 20 حرفاً.',

            'capacity.required' => 'السعة مطلوبة.',
            'capacity.integer' => 'السعة يجب أن تكون عدد صحيح.',

            'stars.required' => 'تصنيف النجوم مطلوب.',
            'stars.in' => 'تصنيف النجوم يجب أن يكون واحد من: one, two, three, four, five, six, seven.',

            'distance_from_center.required' => 'المسافة من المركز مطلوبة.',
            'distance_from_center.numeric' => 'المسافة من المركز يجب أن تكون رقمية.',
            'distance_from_center.min' => 'المسافة من المركز لا يمكن أن تكون أقل من 0.',

            'note.string' => 'الملاحظة يجب أن تكون نصاً.',
        ];
    }
}
