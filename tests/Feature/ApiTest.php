<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    public function it_can_register()
    {
        $response = $this->registerUser();
        $response->assertStatus(201);
    }

    private function registerUser()
    {
        $userData = [
            'username' => 'abdelilah',
            'email' => 'abdouajjouqa@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        return $this->post('/api/register', $userData);
    }




    /** @test */
    public function it_can_login()
    {
        $this->registerUser();
        $response = $this->loginUser();
        $response->assertStatus(200);

        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $responseData);
    }

    private function loginUser()
    {
        $userData = [
            'email' => 'abdouajjouqa@gmail.com',
            'password' => 'password123',
        ];
        return $this->post('/api/login', $userData);
    }

    private function getToken()
    {
        $response = $this->loginUser();
        return json_decode($response->getContent(), true)['token'];
    }




    /** @test */
    public function it_can_logout()
    {
        $this->registerUser();
        $token = $this->getToken();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/logout');
        $response->assertStatus(200);
    }




    /** @test */
    public function it_can_create_a_tournament()
    {
        $this->registerUser();
        $this->loginUser();
        $token = $this->getToken();
        $form = [
            'title' => 'Tournament Title',
            'start_date' => '2025-03-20',
            'end_date' => '2025-04-20',
            'description' => 'Tournament description',
        ];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post('/api/tournament', $form);
        $response->assertStatus(201);
    }

    private function createTournament(){
        $token = $this->getToken();
        $form = [
            'title' => 'Tournament Title',
            'start_date' => '2025-03-20',
            'end_date' => '2025-04-20',
            'description' => 'Tournament description',
        ];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post('/api/tournament', $form);
        return json_decode($response->getContent(), true);
    }




    /** @test */
    public function it_can_update_a_tournament()
    {
        $this->registerUser();
        $this->loginUser();
        $tournament = $this->createTournament();
        $token = $this->getToken();
        $form = [
            'title' => 'Tournament Title Updated',
            'start_date' => '2025-03-20',
            'end_date' => '2025-04-20',
            'description' => 'Tournament description updated',
        ];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->put('/api/tournament/' . $tournament['id'], $form);
        $response->assertStatus(200);
    }




    /** @test */
    public function it_can_delete_a_tournament()
    {
        $this->registerUser();
        $this->loginUser();
        $tournament = $this->createTournament();
        $token = $this->getToken();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->delete('/api/tournament/' . $tournament['id']);
        $response->assertStatus(204);
    }
}
