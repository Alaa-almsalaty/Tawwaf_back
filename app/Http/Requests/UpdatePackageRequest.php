<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Season;

class UpdatePackageRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('update packages');
    }


    public function rules(): array
    {
        return [
            'package_name' => 'sometimes|string|max:255',
            'package_type' => 'sometimes|string|max:50',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'MKduration' => 'sometimes|integer',
            'MDduration' => 'sometimes|integer',
            'total_price_dinar' => 'nullable|numeric',
            'total_price_usd' => 'nullable|numeric',
            'currency' => 'in:dinar,usd',
            'season' => [
                'sometimes',
                Rule::enum(Season::class)
            ],
            'status' => 'sometimes|boolean',
            'note' => 'nullable|string',
            'tenant_id' => 'sometimes|string|exists:tenants,id',
            'MKHotel' => 'nullable',
            'MDHotel' => 'nullable',
            'new_MKHotel_name' => 'nullable|string|max:255',
            'new_MDHotel_name' => 'nullable|string|max:255',
        ];
    }

    public function UpdatePackage(): array
    {
        return collect($this->validated())
            ->filter(fn($value, $key) => $this->has($key) && $value !== null)
            ->toArray();
    }


}
