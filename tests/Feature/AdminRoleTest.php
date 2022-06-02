<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminRoleTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_role_admin()
    {
        $user = [
            'email' => 'admin@gmail.com',
            'password' => '123456'
        ];

        $response = $this->post('/api/v1/auth/login', $user);

        $this->assertTrue($response['user']['role'] == 1, 200);
        $response->assertStatus(200);
    }

    public function test_role_user()
    {
        $user = [
            'email' => 'qismat.huseynov@optima.az',
            'password' => 'optis123456'
        ];

        $response = $this->post('/api/v1/auth/login', $user);

        $this->assertTrue($response['user']['role'] != 1, 200);
        $response->assertStatus(200);
    }

}
