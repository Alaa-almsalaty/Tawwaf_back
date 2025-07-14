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
            'status' => 'required|in:active,inactive,trial,free',
            'balance' => 'required|numeric|min:0',
            'manager_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'note' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'data' => 'nullable|array',
            //'created_by' => 'required|exists:users,id'
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
            //'created_by' => $this->validated('created_by'),
        ];
    }
}
