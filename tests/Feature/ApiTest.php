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
    }

    private function loginUser()
    {
        $userData = [
            'email' => 'abdouajjouqa@gmail.com',
            'password' => 'password123',
        ];
        $response = $this->post('/api/login', $userData);
        return json_decode($response->getContent())->token ?? null;
    }




    /** @test */
    public function it_can_logout()
    {
        $this->registerUser();
        $token = $this->loginUser();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/logout');
        $response->assertStatus(302);
    }




    /** @test */
    public function it_can_create_a_tournament()
    {
        $this->registerUser();
        $token = $this->loginUser();
        $form = [
            'title' => 'Tournament Title',
            'start_date' => '2025-03-20',
            'end_date' => '2025-04-20',
            'description' => 'Tournament description',
        ];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/tournament', $form);
        $response->assertStatus(201);
    }




    /** @test */
    public function it_can_update_a_tournament()
    {
        $this->registerUser();
        $token = $this->loginUser();

        // Create Tournament
        $form = [
            'title' => 'Tournament Title',
            'start_date' => '2025-03-20',
            'end_date' => '2025-04-20',
            'description' => 'Tournament description',
        ];
        $create = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/tournament', $form);
        $create->assertStatus(201);

        // Update Tournament
        $updateForm = [
            'title' => 'Updated Title',
            'start_date' => '2025-03-20',
            'end_date' => '2025-04-30',
            'description' => 'Updated Description',
        ];
        $postUpdated = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put('/api/tournament/{id}', $updateForm);
        $postUpdated->assertStatus(200);
    }
}
