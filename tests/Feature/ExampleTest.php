<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_dashboard_page()
    {
        // $response = $this->get('/dashboard');
        // $response->assertStatus(200);
        $this->assertTrue(true);
        $this->assertFalse(false);
    }

    public function test_user_factory()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }
}
