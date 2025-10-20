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
            'personal_info.personal_img' => 'required|string',
            'personal_info.passport_no' => 'sometimes|exists:passports,id', // Foreign key to the passport, if applicable


            //passport info
            'passport_no.passport_number' => 'required|string|max:20',
            'passport_no.passport_type' => 'required|in:regular,diplomatic,official,ordinary,other',
            'passport_no.nationality' => 'required|string|max:50',
            'passport_no.issue_date' => 'required|date_format:Y-m-d',
            'passport_no.expiry_date' => 'required|date_format:Y-m-d|after:issue_date',
            'passport_no.issue_place' => 'required|string|max:100',
            'passport_no.birth_place' => 'required|string|max:100',
            'passport_no.issue_authority' => 'nullable|string|max:100',
            'passport_no.passport_img' => 'required|string',
            //'passport.passport_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Passport photo

            // client info
            'client.personal_info_id' => 'sometimes|exists:personal_infos,id', // Foreign key to the personal info
            'client.is_family_master' => 'required|boolean', // Indicates if the client is a family master
            'client.register_date' => 'required|date_format:Y-m-d', // Date when the client was registered
            'client.register_state' => 'required|in:pending,completed', // Registration state of the client
            'client.MuhramID' => 'nullable|exists:clients,id', // Foreign key to the muhram info, if applicable
            'client.Muhram_relation' => ['nullable', Rule::enum(Muhram::class)], // Relationship of the muhram to the client
            'client.branch_id' => 'nullable|exists:branches,id', // Foreign key to the branch
            'client.family_id' => 'nullable', // Foreign key to the family client, if applicable
            'client.tenant_id' => 'required|exists:tenants,id', // Foreign key to the tenant
            'client.note' => 'nullable|string', // Optional note field for additional information
            'client.created_by' => 'sometimes|exists:users,id', // Foreign key to the user who created the client
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
        // Personal info
        'personal_info.first_name_ar.required' => 'الاسم الأول بالعربية مطلوب.',
        'personal_info.first_name_ar.string' => 'الاسم الأول بالعربية يجب أن يكون نصًا.',
        'personal_info.first_name_ar.max' => 'الاسم الأول بالعربية لا يجب أن يزيد عن 50 حرفًا.',

        'personal_info.first_name_en.required' => 'First name in English is required.',
        'personal_info.first_name_en.string' => 'First name in English must be a string.',
        'personal_info.first_name_en.max' => 'First name in English must not exceed 50 characters.',

        'personal_info.second_name_ar.required' => 'الاسم الثاني بالعربية مطلوب.',
        'personal_info.second_name_ar.string' => 'الاسم الثاني بالعربية يجب أن يكون نصًا.',
        'personal_info.second_name_ar.max' => 'الاسم الثاني بالعربية لا يجب أن يزيد عن 50 حرفًا.',

        'personal_info.second_name_en.required' => 'Second name in English is required.',
        'personal_info.second_name_en.string' => 'Second name in English must be a string.',
        'personal_info.second_name_en.max' => 'Second name in English must not exceed 50 characters.',

        'personal_info.grand_father_name_ar.required' => 'اسم الجد بالعربية مطلوب.',
        'personal_info.grand_father_name_ar.string' => 'اسم الجد بالعربية يجب أن يكون نصًا.',
        'personal_info.grand_father_name_ar.max' => 'اسم الجد بالعربية لا يجب أن يزيد عن 50 حرفًا.',

        'personal_info.grand_father_name_en.required' => 'Grandfather name in English is required.',
        'personal_info.grand_father_name_en.string' => 'Grandfather name in English must be a string.',
        'personal_info.grand_father_name_en.max' => 'Grandfather name in English must not exceed 50 characters.',

        'personal_info.last_name_ar.required' => 'اسم العائلة بالعربية مطلوب.',
        'personal_info.last_name_ar.string' => 'اسم العائلة بالعربية يجب أن يكون نصًا.',
        'personal_info.last_name_ar.max' => 'اسم العائلة بالعربية لا يجب أن يزيد عن 50 حرفًا.',

        'personal_info.last_name_en.required' => 'Last name in English is required.',
        'personal_info.last_name_en.string' => 'Last name in English must be a string.',
        'personal_info.last_name_en.max' => 'Last name in English must not exceed 50 characters.',

        'personal_info.DOB.required' => 'تاريخ الميلاد مطلوب.',
        'personal_info.DOB.date_format' => 'تاريخ الميلاد يجب أن يكون بالتنسيق YYYY-MM-DD.',

        'personal_info.family_status.required' => 'حالة الأسرة مطلوبة.',
        'personal_info.family_status.in' => 'حالة الأسرة يجب أن تكون واحدة من: single, married, divorced, widowed.',

        'personal_info.gender.required' => 'الجنس مطلوب.',
        'personal_info.gender.in' => 'الجنس يجب أن يكون إما female أو male.',

        'personal_info.medical_status.required' => 'الحالة الصحية مطلوبة.',
        'personal_info.medical_status.in' => 'الحالة الصحية يجب أن تكون واحدة من: healthy, sick, disabled.',

        'personal_info.phone.string' => 'رقم الهاتف يجب أن يكون نصًا.',
        'personal_info.phone.max' => 'رقم الهاتف لا يجب أن يزيد عن 20 حرفًا.',

        'personal_info.personal_img.required' => 'صورة الشخصية مطلوبة.',
        'personal_info.personal_img.string' => 'صورة الشخصية يجب أن تكون نصًا.',

        'personal_info.passport_no.exists' => 'رقم جواز السفر غير موجود.',

        // Passport info
        'passport_no.passport_number.required' => 'رقم جواز السفر مطلوب.',
        'passport_no.passport_number.string' => 'رقم جواز السفر يجب أن يكون نصًا.',
        'passport_no.passport_number.max' => 'رقم جواز السفر لا يجب أن يزيد عن 20 حرفًا.',

        'passport_no.passport_type.required' => 'نوع جواز السفر مطلوب.',
        'passport_no.passport_type.in' => 'نوع جواز السفر يجب أن يكون واحدًا من: regular, diplomatic, official, ordinary, other.',

        'passport_no.nationality.required' => 'الجنسية مطلوبة.',
        'passport_no.nationality.string' => 'الجنسية يجب أن تكون نصًا.',
        'passport_no.nationality.max' => 'الجنسية لا يجب أن تزيد عن 50 حرفًا.',

        'passport_no.issue_date.required' => 'تاريخ الإصدار مطلوب.',
        'passport_no.issue_date.date_format' => 'تاريخ الإصدار يجب أن يكون بالتنسيق YYYY-MM-DD.',

        'passport_no.expiry_date.required' => 'تاريخ الانتهاء مطلوب.',
        'passport_no.expiry_date.date_format' => 'تاريخ الانتهاء يجب أن يكون بالتنسيق YYYY-MM-DD.',
        'passport_no.expiry_date.after' => 'تاريخ الانتهاء يجب أن يكون بعد تاريخ الإصدار.',

        'passport_no.issue_place.required' => 'مكان الإصدار مطلوب.',
        'passport_no.issue_place.string' => 'مكان الإصدار يجب أن يكون نصًا.',
        'passport_no.issue_place.max' => 'مكان الإصدار لا يجب أن يزيد عن 100 حرف.',

        'passport_no.birth_place.required' => 'مكان الميلاد مطلوب.',
        'passport_no.birth_place.string' => 'مكان الميلاد يجب أن يكون نصًا.',
        'passport_no.birth_place.max' => 'مكان الميلاد لا يجب أن يزيد عن 100 حرف.',

        'passport_no.issue_authority.string' => 'جهة الإصدار يجب أن تكون نصًا.',
        'passport_no.issue_authority.max' => 'جهة الإصدار لا يجب أن تزيد عن 100 حرف.',

        'passport_no.passport_img.required' => 'صورة جواز السفر مطلوبة.',
        'passport_no.passport_img.string' => 'صورة جواز السفر يجب أن تكون نصًا.',

        // Client info
        'client.personal_info_id.exists' => 'معلومات الشخصية غير موجودة.',
        'client.is_family_master.required' => 'حقل تحديد إذا كان رب الأسرة مطلوب.',
        'client.is_family_master.boolean' => 'حقل رب الأسرة يجب أن يكون true أو false.',
        'client.register_date.required' => 'تاريخ التسجيل مطلوب.',
        'client.register_date.date_format' => 'تاريخ التسجيل يجب أن يكون بالتنسيق YYYY-MM-DD.',
        'client.register_state.required' => 'حالة التسجيل مطلوبة.',
        'client.register_state.in' => 'حالة التسجيل يجب أن تكون إما pending أو completed.',
        'client.MuhramID.exists' => 'رقم المحرم غير موجود.',
        'client.Muhram_relation.in' => 'علاقة المحرم غير صحيحة.',

        'client.branch_id.exists' => 'الفرع غير موجود.',
        'client.tenant_id.required' => 'رقم الشركة مطلوب.',
        'client.tenant_id.exists' => 'رقم الشركة غير موجود.',

        // Family info
        'family.family_master_id.exists' => 'رقم رب الأسرة غير موجود.',
        'family.family_size.integer' => 'حجم الأسرة يجب أن يكون رقمًا صحيحًا.',
        'family.family_size.min' => 'حجم الأسرة لا يمكن أن يقل عن 1.',
        'family.family_name_ar.string' => 'اسم العائلة بالعربية يجب أن يكون نصًا.',
        'family.family_name_ar.max' => 'اسم العائلة بالعربية لا يجب أن يزيد عن 100 حرف.',
        'family.family_name_en.string' => 'اسم العائلة بالإنجليزية يجب أن يكون نصًا.',
        'family.family_name_en.max' => 'اسم العائلة بالإنجليزية لا يجب أن يزيد عن 100 حرف.',
        'family.tenant_id.exists' => 'رقم الشركة غير موجود.',
    ];
    }

}
