<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBranchRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'email' => 'nullable|email',
            'manager_name' => 'nullable|string|max:100',
            'capacity' => 'nullable|integer',
            'phone' => 'nullable|string|max:20',
            'note' => 'nullable|string',
            'active' => 'sometimes|boolean',
            'tenant_id' => 'required|exists:tenants,id',
        ];
    }

    public function CreateBranchRequest(): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'city' => $this->city,
            'email' => $this->email,
            'manager_name' => $this->manager_name ?? null,
            'capacity' => $this->capacity ?? null,
            'phone' => $this->phone,
            'note' => $this->note ?? null,
            'tenant_id' => $this->tenant_id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.string'         => 'اسم الفرع يجب أن يكون نصاً.',
            'name.max'            => 'اسم الفرع يجب ألا يتجاوز 100 حرف.',

            'address.required'    => 'العنوان مطلوب.',
            'address.string'      => 'العنوان يجب أن يكون نصاً.',
            'address.max'         => 'العنوان يجب ألا يتجاوز 255 حرف.',

            'city.required'       => 'المدينة مطلوبة.',
            'city.string'         => 'اسم المدينة يجب أن يكون نصاً.',
            'city.max'            => 'اسم المدينة يجب ألا يتجاوز 100 حرف.',

            'email.email'         => 'يرجى إدخال بريد إلكتروني صالح.',

            'manager_name.string' => 'اسم المدير يجب أن يكون نصاً.',
            'manager_name.max'    => 'اسم المدير يجب ألا يتجاوز 100 حرف.',

            'capacity.integer'    => 'السعة يجب أن تكون رقماً صحيحاً.',

            'phone.string'        => 'رقم الهاتف يجب أن يكون نصاً.',
            'phone.max'           => 'رقم الهاتف يجب ألا يتجاوز 20 حرف.',

            'note.string'         => 'الملاحظات يجب أن تكون نصاً.',

            'active.boolean'      => 'حقل التفعيل يجب أن يكون صحيح أو خطأ.',

            'tenant_id.required'  => 'معرّف الشركة مطلوب.',
            'tenant_id.exists'    => 'معرف الشركة المحدد غير موجود.',
        ];
    }

}
