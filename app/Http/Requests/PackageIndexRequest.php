<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PackageIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'   => ['nullable', Rule::in(['basic','premium','vip','economy'])], // adjust to your enums
            'currency' => ['nullable', Rule::in(['usd','dinar'])],
            'price'  => ['nullable','regex:/^\d+\s*-\s*\d+$|^\d+\s*\+$/'], // "1000-1500" or "2000+"
            'date'   => ['nullable','date_format:Y-m-d'],
            'date_tolerance_days' => ['nullable','integer','min:0','max:14'], // default 3
            'distance' => ['nullable','integer','min:0'], // meters (e.g., 0, 200)
            'per_page' => ['nullable','integer','min:1','max:100'],
        ];
    }
}
