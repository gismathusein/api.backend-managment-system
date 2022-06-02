<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_users()
    {
        $response = $this->get('api/v1/settings/users', ['Authorization' => 'Bearer ' . $this->adminToken]);
        $response->assertStatus(200);
    }

}
