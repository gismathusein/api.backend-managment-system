<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompanyTest extends TestCase
{

    public function test_create_company()
    {
        $this->withoutExceptionHandling();
        $data = [
            'name' => 'Optima',
            'email' => 'contact@optima.az',
            'phone' => '506699988',
            'address' => 'Aynalı Plaza',
            'status' => 1
        ];
        $response = $this->post('/api/v1/settings/companies', $data, ['Authorization' => 'Bearer ' . $this->adminToken]);

        $response->assertStatus(201);
    }

    public function test_update_company()
    {
        $this->withoutExceptionHandling();
        Storage::fake('random');
        $data = [
            'address' => 'Aynalıa Plaza',
            'logo' => UploadedFile::fake()->image('random.png')
        ];

        $response = $this->post('/api/v1/settings/companies/1', $data, ['Authorization' => 'Bearer ' . $this->adminToken]);
        $response->assertStatus(200);
    }

    public function test_get_companies()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/api/v1/settings/companies', ['Authorization' => 'Bearer ' . $this->adminToken]);
        $response->assertStatus(200);
    }

    public function test_destroy_company()
    {
        $this->withoutExceptionHandling();
        $response = $this->delete('/api/v1/settings/companies/destroy/1', [], ['Authorization' => 'Bearer ' . $this->adminToken]);
        $response->assertStatus(200);
    }

    public function test_get_single_company()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/api/v1/settings/companies/1', ['Authorization' => 'Bearer ' . $this->adminToken]);
        $response->assertStatus(200);
    }

    public function test_company_users($data = ['limit' => 5])
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/api/v1/settings/companies/1/users?limit=5', ['Authorization' => 'Bearer ' . $this->adminToken]);
        $response->assertStatus(200);
    }


}
