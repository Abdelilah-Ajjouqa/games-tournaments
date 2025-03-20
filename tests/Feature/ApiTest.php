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

    /** @test */
    public function it_can_login(){

        $registerData = [
            'username'=>'abdelilah',
            'email'=>'abdouajjouqa@gmail.com',
            'password'=>'password123',
            'password_confirmation'=>'password123',
        ];

        $userData = [
            'email'=>'abdouajjouqa@gmail.com',
            'password'=>'password123',
        ];

        $register = $this->post('/api/register', $registerData);

        $response = $this->post('/api/login', $userData);

        $register->assertStatus(201);
        $response->assertStatus(200);
    }
}
