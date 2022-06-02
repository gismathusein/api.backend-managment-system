<?php

namespace Tests\Feature;

use Tests\TestCase;

class DepartmentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_departments()
    {
        $response = $this->get('/api/v1/settings/departments', ['Authorization' => 'Bearer ' . $this->adminToken]);
        $response->assertStatus(200);
    }

    public function test_create_department($data = ['company_id' => 1, 'name' => 'Test Department'])
    {
        $response = $this->post('api/v1/settings/departments', $data, ['Authorization' => 'Bearer ' . $this->adminToken]);
        $response->assertStatus(201);
    }

    public function test_single_department($id = 1)
    {
        $response = $this->get('api/v1/settings/departments/' . $id, ['Authorization' => 'Bearer ' . $this->adminToken]);
        $response->assertStatus(200);
    }

    public function test_update_department($data = ['name' => 'Test update Department'], $id = 1)
    {
        $response = $this->put('api/v1/settings/departments/' . $id, $data, ['Authorization' => 'Bearer ' . $this->adminToken]);
        $response->assertStatus(200);
    }

//    public function test_delete_department($id = 1)
//    {
//        $response = $this->delete('/api/v1/settings/departments/destroy/' . $id, [], ['Authorization' => 'Bearer ' . $this->adminToken]);
//        $this->assertTrue($response['statusCode'] = 400, $response['data']);
//        $this->assertTrue($response['statusCode'] = 200, $response['data']);
//    }
}
