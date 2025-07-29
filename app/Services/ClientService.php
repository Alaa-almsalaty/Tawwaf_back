<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Passport;
use App\Models\PersonalInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientService
{
    public function store(array $data): Client
    {
        return DB::transaction(function () use ($data) {
            // Step 1: Create Passport
            $passport = Passport::create([
                'passport_number' => $data['passport_no']['passport_number'],
                'passport_type' => $data['passport_no']['passport_type'],
                'nationality' => $data['passport_no']['nationality'],
                'issue_date' => $data['passport_no']['issue_date'],
                'expiry_date' => $data['passport_no']['expiry_date'],
                'issue_place' => $data['passport_no']['issue_place'],
                'birth_place' => $data['passport_no']['birth_place'],
                'issue_authority' => $data['passport_no']['issue_authority'] ?? null,
                'passport_img' => $data['passport_no']['passport_img'], // assuming file is already stored
            ]);

            // Step 2: Create Personal Info
            $personalInfo = PersonalInfo::create([
                'first_name_ar' => $data['personal_info']['first_name_ar'],
                'first_name_en' => $data['personal_info']['first_name_en'],
                'second_name_ar' => $data['personal_info']['second_name_ar'],
                'second_name_en' => $data['personal_info']['second_name_en'],
                'grand_father_name_ar' => $data['personal_info']['grand_father_name_ar'],
                'grand_father_name_en' => $data['personal_info']['grand_father_name_en'],
                'last_name_ar' => $data['personal_info']['last_name_ar'],
                'last_name_en' => $data['personal_info']['last_name_en'],
                'DOB' => $data['personal_info']['DOB'],
                'family_status' => $data['personal_info']['family_status'],
                'gender' => $data['personal_info']['gender'],
                'medical_status' => $data['personal_info']['medical_status'],
                'phone' => $data['personal_info']['phone'] ?? null,
                'passport_no' => $passport->id,
            ]);

            // Step 3: Create Client
            $client = Client::create([
                'is_family_master' => $data['client']['is_family_master'],
                'register_date' => $data['client']['register_date'],
                'register_state' => $data['client']['register_state'],
                'branch_id' => $data['client']['branch_id'],
                'tenant_id' => $data['client']['tenant_id'],
                'personal_info_id' => $personalInfo->id,
                'family_id' => $data['client']['family_id'] ?? null,
                'MuhramID' => $data['client']['MuhramID'] ?? null,
                'Muhram_relation' => $data['client']['Muhram_relation'] ?? null,
                'note' => $data['client']['note'] ?? null,
            ]);

            // Step 4: If client is a family master, create a Family record
            if ($client->is_family_master) {
                $family = $client->family()->create([
                    'family_master_id' => $client->id,
                    'tenant_id' => $client->tenant_id,
                    'family_name_ar' => $personalInfo->last_name_ar,
                    'family_name_en' => $personalInfo->last_name_en,
                    //'branch_id' => $client->branch_id,
                    'family_size' => 1, // Initial size is 1 for the family master
                    'note' => 'Auto-created for family master client.',
                ]);

                // Optionally update client with the newly created family_id
                $client->update([
                    'family_id' => $family->id,
                ]);
            } else {
                // If not a family master, check if family_id is provided
                if (isset($data['client']['family_id'])) {
                    $family = $client->family()->find($data['client']['family_id']);
                    if ($family) {
                        $family->increment('family_size'); // Increment family size
                    }
                }
            }


            // step 5 : adjust the tenant balance after creating the client
            $tenant = $client->tenant;
            if ($tenant) {
                $tenant->balance -= 1.0;
                $tenant->save();
            } else {
                Log::warning('Client created without a valid tenant', ['client_id' => $client->id]);
            }
            // step 6: return the client
            $client->load(['family', 'personalInfo.passport', 'muhram', 'branch']);
            return $client;


        });
    }


    public function update(Client $client, array $data): Client
    {
        return DB::transaction(function () use ($client, $data) {
            // Update Passport if present
            if (isset($data['passport_no'])) {
                $client->personalInfo->passport->update($data['passport_no']);
            }

            // Update Personal Info if present
            if (isset($data['personal_info'])) {
                $client->personalInfo->update($data['personal_info']);
            }

            // Update Client fields if present
            if (isset($data['client'])) {
                $client->update($data['client']);
            }

            // Update Family if present
            if (isset($data['family']) && $client->family) {
                $client->family->update($data['family']);
            }

            return $client->fresh();
        });
    }
}
