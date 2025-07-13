<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TenantDomainAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_tenant_and_accesses_a_tenant_scoped_route()
    {
        // Step 1: Create a super admin user and authenticate
        $user = User::factory()->create([
            'role' => 'super',
        ]);
        $this->actingAs($user, 'sanctum');

        // Step 2: Create tenant through API
        $response = $this->postJson('/api/tenants', [
            'company_name' => 'Test Company',
            'address' => '123 Main St',
            'city' => 'Test City',
            'email' => 'testcompany@example.com',
            'status' => 'active',
            'balance' => 1000,
            'manager_name' => 'Manager',
            'phone' => '1234567890',
            'note' => 'Test note',
            'created_by' => $user->id,
        ]);

        $response->assertStatus(201);

        $tenantId = $response->json('tenant.id');
        $domain = $response->json('domain');

        // Step 3: Fetch the created tenant model
        $tenant = Tenant::find($tenantId);
        $this->assertNotNull($tenant);

        // Step 4: Initialize tenancy (simulates tenant subdomain routing)
        tenancy()->initialize($tenant);

        // Step 5: Create a tenant user and authenticate
        $tenantUser = User::factory()->create([
            'role' => 'manager',
        ]);
        $this->actingAs($tenantUser, 'sanctum');
        // Step 4: Setup HTTP_HOST to simulate tenant subdomain
        $server = ['HTTP_HOST' => $domain];

        // Step 6: Prepare client data and POST
        $clientData = [
            'passport' => [
                'passport_number' => 'TST123456',
                'passport_type' => 'regular',
                'nationality' => 'Testland',
                'issue_date' => '2022-01-01',
                'expiry_date' => '2032-01-01',
                'issue_place' => 'Test City',
                'birth_place' => 'Test City',
                'issue_authority' => 'Test Authority',
                'passport_img' => 'passport.jpg',
            ],
            'personal' => [
                'first_name_ar' => 'اختبار',
                'first_name_en' => 'Test',
                'second_name_ar' => 'مستخدم',
                'second_name_en' => 'User',
                'grand_father_name_ar' => 'جد',
                'grand_father_name_en' => 'Grand',
                'last_name_ar' => 'أخير',
                'last_name_en' => 'Last',
                'DOB' => '1995-05-05',
                'family_status' => 'single',
                'gender' => 'male',
                'medical_status' => 'healthy',
                'phone' => '0123456789',
            ],
            'client' => [
                'is_family_master' => true,
                'register_date' => '2024-01-01',
                'register_state' => 'completed',
                'branch_id' => null,
                'tenant_id' => $tenantId,
                'family_id' => null,
                'MuhramID' => null,
                'Muhram_relation' => null,
                'note' => 'Test client note',
            ],
        ];
        dd($clientData);
        // Step 7: Create client via tenant API route
        $createClientResponse = $this->postJson('/api/clients', $clientData);
        $createClientResponse->assertStatus(201);
        $this->assertEquals('Client created successfully', $createClientResponse->json('message'));

        // Step 8: Fetch clients to confirm presence
        $tenantRouteResponse = $this->getJson('/api/clients');
        $tenantRouteResponse->assertStatus(200);
        $this->assertTrue(
            collect($tenantRouteResponse->json())->contains(
                fn($client) => $client['note'] === 'Test client note'
            )
        );
    }
}
