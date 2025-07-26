<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;
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
        return true;
        //return auth()->user()->IsManager() || auth()->user()->IsEmployee();
    }


    public function rules(): array
    {
        $rules = [
            // Personal info
            'personal_info.first_name_ar' => 'required|string|max:50',
            'personal_info.first_name_en' => 'required|string|max:50',
            'personal_info.second_name_ar' => 'required|string|max:50',
            'personal_info.second_name_en' => 'required|string|max:50',
            'personal_info.grand_father_name_ar' => 'required|string|max:50',
            'personal_info.grand_father_name_en' => 'required|string|max:50',
            'personal_info.last_name_ar' => 'required|string|max:50',
            'personal_info.last_name_en' => 'required|string|max:50',
            'personal_info.DOB' => 'required|date_format:Y-m-d',
            'personal_info.family_status' => 'required|in:single,married,divorced,widowed',
            'personal_info.gender' => 'required|in:female,male',
            'personal_info.medical_status' => 'required|in:healthy,sick,disabled',
            'personal_info.phone' => 'nullable|string|max:20',
            'personal_info.passport_no' => 'sometimes|exists:passports,id', // Foreign key to the passport, if applicable


            //passport info
            'passport.passport_number' => 'required|string|max:20',
            'passport.passport_type' => 'required|in:regular,diplomatic,official,ordinary,other',
            'passport.nationality' => 'required|string|max:50',
            'passport.issue_date' => 'required|date_format:Y-m-d',
            'passport.expiry_date' => 'required|date_format:Y-m-d|after:issue_date',
            'passport.issue_place' => 'required|string|max:100',
            'passport.birth_place' => 'required|string|max:100',
            'passport.issue_authority' => 'nullable|string|max:100',
            'passport.passport_img' => 'required|string',
            //'passport.passport_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Passport photo

            // client info
            'client.personal_info_id' => 'sometimes|exists:personal_infos,id', // Foreign key to the personal info
            'client.is_family_master' => 'required|boolean', // Indicates if the client is a family master
            'client.register_date' => 'required|date_format:Y-m-d', // Date when the client was registered
            'client.register_state' => 'required|in:pending,completed', // Registration state of the client
            'client.MuhramID' => 'nullable|exists:clients,id', // Foreign key to the muhram info, if applicable
            'client.Muhram_relation' => ['nullable', Rule::enum(Muhram::class)], // Relationship of the muhram to the client
            'client.branch_id' => 'nullable|exists:branches,id', // Foreign key to the branch
            'client.family_id' => 'nullable|exists:families,id', // Foreign key to the family client, if applicable
            'client.tenant_id' => 'required|exists:tenants,id', // Foreign key to the tenant
            'client.note' => 'nullable|string', // Optional note field for additional information

            // family info
            'family.family_master_id' => 'sometimes|exists:clients,id', // Foreign key to the family master client
            'family.family_size' => 'sometimes|integer|min:1', // Number of members in the family
            'family.family_name_ar' => 'nullable|string|max:100', // Family name in Arabic
            'family.family_name_en' => 'nullable|string|max:100', // Family name in English
            'family.tenant_id' => 'sometimes|exists:tenants,id', // Foreign key to the tenant
            'family.note' => 'nullable|string', // Optional note field for additional information
        ];
        return $rules;
    }

    public function messages(): array
    {
        return [
            'passport.passport_number.required' => 'Passport number is required.',
            'personal_info.first_name_ar.required' => 'First name in Arabic is required.',
            'client.is_family_master.boolean' => 'Is family master must be true or false.',
            'client.register_date.date_format' => 'Register date must be in Y-m-d format.',
            'client.register_state.in' => 'Register state must be either pending or completed.',
            'family.family_master_id.required' => 'Family master ID is required.',
        ];
    }
}
