<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
        //return auth()->user()->isSuperAdmin();
    }


    public function rules(): array
    {
        return [
            'company_name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:100',
            'email' => 'sometimes|email',
            'status' => 'sometimes|in:monthly,year,trailer',
            'balance' => 'sometimes|numeric|min:0',
            'manager_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'note' => 'nullable|string',
            'logo' => 'nullable|string',
            'data' => 'nullable|array',
            'active' => 'sometimes|boolean',
            'season' => 'required|integer',
            'created_by' => 'sometimes|exists:users,id',
        ];
    }

        public function UpdateCompany(): array
    {
        return collect($this->all())
            ->filter(fn($value, $key) => $this->has($key) && $value !== null)
            ->toArray();
    }
}
