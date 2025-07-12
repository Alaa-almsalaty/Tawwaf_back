<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Muhram;
use Illuminate\Validation\Rule;

class AddClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->IsManager() || auth()->user()->IsEmployee();
    }


    public function rules(): array
    {
        return [
            //personal info
            'first_name_ar' => 'required|string|max:50',
            'first_name_en' => 'required|string|max:50',
            'second_name_ar' => 'required|string|max:50',
            'second_name_en' => 'required|string|max:50',
            'grand_father_name_ar' => 'required|string|max:50',
            'grand_father_name_en' => 'required|string|max:50',
            'last_name_ar' => 'required|string|max:50',
            'last_name_en' => 'required|string|max:50',
            'DOB' => 'required|date_format:Y-m-d',
            'family_status' => 'required|in:single,married,divorced,widowed',
            'gender' => 'required|in:female,male',
            'medical_status' => 'required|in:healthy,sick,disabled',
            'phone' => 'nullable|string|max:20',
            'passport_no' => 'required|exists:passports,id', // Foreign key to the passport, if applicable

            //passport info
            'passport_number' => 'required|string|max:20',
            'passport_type' => 'required|in:regular,diplomatic,official,ordinary,other',
            'nationality' => 'required|string|max:50',
            'issue_date' => 'required|date_format:Y-m-d',
            'expiry_date' => 'required|date_format:Y-m-d|after:issue_date',
            'issue_place' => 'required|string|max:100',
            'birth_place' => 'required|string|max:100',
            'issue_authority' => 'nullable|string|max:100',
            'passport_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Passport photo

            // client info
            'personal_info_id' => 'required|exists:personal_infos,id', // Foreign key to the personal info
            'is_family_master' => 'required|boolean', // Indicates if the client is a family master
            'register_date' => 'required|date_format:Y-m-d', // Date when the client was registered
            'register_state' => 'required|in:pending,completed', // Registration state of the client
            'MuhramID' => 'nullable|exists:clients,id', // Foreign key to the muhram info, if applicable
            'Muhram_relation' => ['nullable', Rule::enum(Muhram::class)], // Relationship of the muhram to the client
            'branch_id' => 'required|exists:branches,id', // Foreign key to the branch
            'family_id' => 'nullable|exists:families,id', // Foreign key to the family client, if applicable
            'tenant_id' => 'required|exists:tenants,id', // Foreign key to the tenant
            'note' => 'nullable|string', // Optional note field for additional information
        ];
    }
}
