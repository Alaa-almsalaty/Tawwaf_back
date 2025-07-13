<?php

namespace Tests\Unit\Services;

use App\Models\Client;
use App\Models\Passport;
use App\Models\PersonalInfo;
use App\Models\Family;
use App\Services\ClientService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_client_with_passport_and_personal_info_and_family_master()
    {
        $service = new ClientService();

        $data = [
            'passport' => [
                'passport_number' => 'A1234567',
                'passport_type' => 'regular',
                'nationality' => 'Egyptian',
                'issue_date' => '2020-01-01',
                'expiry_date' => '2030-01-01',
                'issue_place' => 'Cairo',
                'birth_place' => 'Cairo',
                'issue_authority' => 'Authority',
                'passport_img' => 'passport.jpg',
            ],
            'personal' => [
                'first_name_ar' => 'أحمد',
                'first_name_en' => 'Ahmed',
                'second_name_ar' => 'محمد',
                'second_name_en' => 'Mohamed',
                'grand_father_name_ar' => 'علي',
                'grand_father_name_en' => 'Ali',
                'last_name_ar' => 'سعيد',
                'last_name_en' => 'Saeed',
                'DOB' => '1990-01-01',
                'family_status' => 'single',
                'gender' => 'male',
                'medical_status' => 'healthy',
                'phone' => '01000000000',
                'passport_no' => Passport::factory()->create()->id, // Create a passport first

            ],
            'client' => [
                'is_family_master' => true,
                'register_date' => '2024-01-01',
                'register_state' => 'completed',
                'branch_id' => 2,
                'tenant_id' => 2,
                'family_id' => null,
                'MuhramID' => null,
                'Muhram_relation' => null,
                'note' => 'Test note',
            ],
        ];

        // You may need to create related branch and tenant records first
        \App\Models\Branch::factory()->create(['id' => 2]);
        \App\Models\Tenant::factory()->create(['id' => 2]);

        $client = $service->store($data);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertDatabaseHas('passports', ['passport_number' => 'A1234567']);
        $this->assertDatabaseHas('personal_infos', ['first_name_en' => 'Ahmed']);
        $this->assertDatabaseHas('clients', ['note' => 'Test note']);
        $this->assertTrue($client->is_family_master);

        // Check family was created and linked
        $this->assertNotNull($client->family_id);
        $this->assertDatabaseHas('families', [
            'family_master_id' => $client->id,
            'family_size' => 1,
        ]);
    }
}
