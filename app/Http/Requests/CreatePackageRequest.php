<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Season;

class CreatePackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('create packages');
    }

    public function rules(): array
    {
        return [
            'package_name' => 'required|string|max:255',
            'package_type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'MKduration' => 'required|integer',
            'MDduration' => 'required|integer',
            'total_price_dinar' => 'nullable|numeric',
            'total_price_usd' => 'nullable|numeric',
            'currency' => 'in:dinar,usd',
            'season' => [
                'required',
                Rule::enum(Season::class)
            ],
            'status' => 'sometimes|boolean',
            'note' => 'nullable|string',
            'tenant_id' => 'required|string|exists:tenants,id',
            'MKHotel' => 'nullable|exists:hotels,id',
            'MDHotel' => 'nullable|exists:hotels,id',
            'image' => 'nullable|string',
            'new_MKHotel_name' => 'nullable|string|max:255',
            'new_MDHotel_name' => 'nullable|string|max:255',
        ];
    }

    public function CreatePackageRequest(): array
    {
        return [
            'package_name' => $this->package_name,
            'package_type' => $this->package_type,
            'description' => $this->description ?? null,
            'features' => $this->features ?? null,
            'start_date' => $this->start_date ?? null,
            'end_date' => $this->end_date ?? null,
            'MKduration' => $this->MKduration,
            'MDduration' => $this->MDduration,
            'total_price_dinar' => $this->total_price_dinar ?? null,
            'total_price_usd' => $this->total_price_usd ?? null,
            'currency' => $this->currency ?? 'dinar',
            'season' => $this->season,
            //'status' => $this->status ?? 'active',
            'note' => $this->note ?? null,
            'tenant_id' => $this->tenant_id,
            'MKHotel' => $this->MKHotel,
            'MDHotel' => $this->MDHotel,
            'image' => $this->image ?? null,
            'MDHotel' => $this->MDHotel,
            'new_MKHotel_name' => $this->new_MKHotel_name ?? null,
            'new_MDHotel_name' => $this->new_MDHotel_name ?? null,
        ];
    }

    public function messages(): array
    {
        return [

            'package_name.required' => 'اسم الباقة مطلوب.',
            'package_name.string' => 'اسم الباقة يجب أن يكون نصاً.',
            'package_name.max' => 'اسم الباقة يجب ألا يتجاوز 255 حرفاً.',

            'package_type.required' => 'نوع الباقة مطلوب.',
            'package_type.string' => 'نوع الباقة يجب أن يكون نصاً.',
            'package_type.max' => 'نوع الباقة يجب ألا يتجاوز 50 حرفاً.',

            'description.string' => 'الوصف يجب أن يكون نصاً.',
            'features.string' => 'المميزات يجب أن تكون نصاً.',

            'start_date.date' => 'تاريخ البدء يجب أن يكون تاريخاً صالحاً.',
            'end_date.date' => 'تاريخ الانتهاء يجب أن يكون تاريخاً صالحاً.',

            'MKduration.required' => 'مدة الإقامة في مكة مطلوبة.',
            'MKduration.integer' => 'مدة الإقامة في مكة يجب أن تكون رقماً صحيحاً.',
            'MDduration.required' => 'مدة الإقامة في المدينة مطلوبة.',
            'MDduration.integer' => 'مدة الإقامة في المدينة يجب أن تكون رقماً صحيحاً.',

            'total_price_dinar.numeric' => 'السعر الإجمالي بالدينار يجب أن يكون رقماً.',
            'total_price_usd.numeric' => 'السعر الإجمالي بالدولار يجب أن يكون رقماً.',
            'currency.in' => 'العملة يجب أن تكون واحدة من: الدينار أو الدولار.',

            'season.required' => 'الموسم مطلوب.',
            'season.enum' => 'الموسم يجب أن يكون واحداً من: عمرة، حج، رمضان، عيد، عادي.',

            'note.string' => 'الملاحظة يجب أن تكون نصاً.',


        ];
    }
}
