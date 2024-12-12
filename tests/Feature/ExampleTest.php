<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_redirects_to_login(): void
    {
        $response = $this->get('/'); // Halaman utama
    
        $response->assertStatus(302); // Redirect ke login
        $response->assertRedirect('/login'); // Pastikan diarahkan ke halaman login
    }
    
}
