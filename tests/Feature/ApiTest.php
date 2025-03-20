<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function home(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_register(){
        
        $userData = [
            'username'=>'abdelilah',
            'email'=>'abdouajjouqa@gmail.com',
            'password'=>'password123',
            'password_confirmation'=>'password123',
        ];

        $response = $this->post('/api/register', $userData);

        $response->assertStatus(201);
    }
}
