<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\Muhram;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
        //return auth()->user()->IsManager() || auth()->user()->IsEmployee();
    }

    public function rules(): array
    {
        return [
            'personal' => ['sometimes', 'array'],
            'passport' => ['sometimes', 'array'],
            'client' => ['sometimes', 'array'],
            'family' => ['sometimes', 'array'],
        ] + $this->personalInfoRules() + $this->passportRules() + $this->clientRules() + $this->familyRules();
    }

    private function personalInfoRules(): array
    {
        return [
            'personal.first_name_ar' => 'sometimes|string|max:50',
            'personal.first_name_en' => 'sometimes|string|max:50',
            'personal.second_name_ar' => 'sometimes|string|max:50',
            'personal.second_name_en' => 'sometimes|string|max:50',
            'personal.grand_father_name_ar' => 'sometimes|string|max:50',
            'personal.grand_father_name_en' => 'sometimes|string|max:50',
            'personal.last_name_ar' => 'sometimes|string|max:50',
            'personal.last_name_en' => 'sometimes|string|max:50',
            'personal.DOB' => 'sometimes|date_format:Y-m-d',
            'personal.family_status' => 'sometimes|in:single,married,divorced,widowed',
            'personal.gender' => 'sometimes|in:female,male',
            'personal.medical_status' => 'sometimes|in:healthy,sick,disabled',
            'personal.phone' => 'nullable|string|max:20',
            'personal.passport_no' => 'sometimes|exists:passports,id',
        ];
    }

    private function passportRules(): array
    {
        return [
            'passport.passport_number' => 'sometimes|string|max:20',
            'passport.passport_type' => 'sometimes|in:regular,diplomatic,official,ordinary,other',
            'passport.nationality' => 'sometimes|string|max:50',
            'passport.issue_date' => 'sometimes|date_format:Y-m-d',
            'passport.expiry_date' => 'sometimes|date_format:Y-m-d|after:passport.issue_date',
            'passport.issue_place' => 'sometimes|string|max:100',
            'passport.birth_place' => 'sometimes|string|max:100',
            'passport.issue_authority' => 'nullable|string|max:100',
            'passport.passport_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    private function clientRules(): array
    {
        return [
            'client.personal_info_id' => 'sometimes|exists:personal_infos,id',
            'client.is_family_master' => 'sometimes|boolean',
            'client.register_date' => 'sometimes|date_format:Y-m-d',
            'client.register_state' => 'sometimes|in:pending,completed',
            'client.MuhramID' => 'nullable|exists:clients,id',
            'client.Muhram_relation' => ['nullable', Rule::enum(Muhram::class)],
            'client.branch_id' => 'sometimes|exists:branches,id',
            'client.family_id' => 'nullable|exists:families,id',
            'client.tenant_id' => 'sometimes|exists:tenants,id',
            'client.note' => 'nullable|string',
        ];
    }

    private function familyRules(): array
    {
        return [
            'family.family_master_id' => 'sometimes|exists:clients,id',
            'family.family_size' => 'sometimes|integer|min:1',
            'family.family_name_ar' => 'nullable|string|max:100',
            'family.family_name_en' => 'nullable|string|max:100',
            'family.tenant_id' => 'sometimes|exists:tenants,id',
            'family.note' => 'nullable|string',
        ];
    }

    public function updateClient(): array
    {
        return collect($this->all())
            ->filter(fn($value, $key) => $this->has($key) && $value !== null)
            ->toArray();
    }
}
