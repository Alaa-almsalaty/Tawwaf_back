<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;


class CreateCompanyRequest extends FormRequest
{

    public function authorize(): bool
    {
        //return auth()->user()->IsSuperAdmin();
        return true;
    }


    public function rules(): array
    {
        Log::info('CreateCompanyRequest rules called');
        return [
            'company_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'email' => 'required|email',
            'status' => 'sometimes|in:monthly,year,trailer',
            'balance' => 'required|numeric|min:0',
            'manager_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'note' => 'nullable|string',
            //'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo' => 'required|string',
            'data' => 'nullable|array',
            'active' => 'required|boolean',
            'season' => 'required|integer',
            'created_by' => 'required|exists:users,id'
        ];
    }

    public function toArrayForTenant(): array
    {
        Log::info('CreateCompanyRequest toArrayForTenant called');
        return [
            'company_name' => $this->validated('company_name'),
            'address' => $this->validated('address'),
            'city' => $this->validated('city'),
            'email' => $this->validated('email'),
            'status' => $this->validated('status'),
            'balance' => $this->validated('balance'),
            'manager_name' => $this->validated('manager_name'),
            'phone' => $this->validated('phone'),
            'note' => $this->validated('note'),
            'logo' => $this->validated('logo'),
            'created_by' => $this->validated('created_by'),
        ];
    }

     public function messages(): array
    {
        return [
            'company_name.required' => 'اسم الشركة مطلوب.',
            'company_name.string'   => 'اسم الشركة يجب أن يكون نصاً.',
            'company_name.max'      => 'اسم الشركة يجب ألا يتجاوز 255 حرفاً.',

            'address.required'      => 'العنوان مطلوب.',
            'address.string'        => 'العنوان يجب أن يكون نصاً.',
            'address.max'           => 'العنوان يجب ألا يتجاوز 255 حرفاً.',

            'city.required'         => 'اسم المدينة مطلوب.',
            'city.string'           => 'اسم المدينة يجب أن يكون نصاً.',
            'city.max'              => 'اسم المدينة يجب ألا يتجاوز 100 حرف.',

            'email.required'        => 'البريد الإلكتروني مطلوب.',
            'email.email'           => 'يرجى إدخال بريد إلكتروني صالح.',

            'status.in'             => 'قيمة الحالة غير صحيحة.',

            'balance.required'      => 'الرصيد مطلوب.',
            'balance.numeric'       => 'الرصيد يجب أن يكون رقماً.',
            'balance.min'           => 'الرصيد لا يمكن أن يكون أقل من صفر.',

            'manager_name.string'   => 'اسم المدير يجب أن يكون نصاً.',
            'manager_name.max'      => 'اسم المدير يجب ألا يتجاوز 100 حرف.',

            'phone.string'          => 'رقم الهاتف يجب أن يكون نصاً.',
            'phone.max'             => 'رقم الهاتف يجب ألا يتجاوز 20 حرفاً.',

            'note.string'           => 'الملاحظات يجب أن تكون نصاً.',

            'logo.image'            => 'يجب أن يكون الشعار صورة.',
            'logo.mimes'            => 'صيغة الشعار يجب أن تكون jpeg أو png أو jpg أو gif أو svg.',
            'logo.max'              => 'حجم الشعار يجب ألا يتجاوز 2 ميجابايت.',

            'data.array'            => 'حقل البيانات يجب أن يكون مصفوفة.',

            'active.required'       => 'حقل التفعيل مطلوب.',
            'active.boolean'        => 'حقل التفعيل يجب أن يكون صحيح أو خطأ.',

            'season.required'       => 'الموسم مطلوب.',
            'season.integer'        => 'الموسم يجب أن يكون رقماً صحيحاً.',

            'created_by.required'   => 'المستخدم المنشئ مطلوب.',
            'created_by.exists'     => 'المستخدم المنشئ غير موجود.',
        ];
    }
}
