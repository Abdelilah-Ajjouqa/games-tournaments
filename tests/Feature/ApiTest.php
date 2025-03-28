<?php

namespace Tests\Feature;

use App\Models\tournament;
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



    // users tests
    /** @test */
    public function it_can_list_tournaments()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get('/api/tournaments');
        $response->assertStatus(200);
    }



    /** @test */
    public function it_can_show_a_tournament()
    {
        $this->registerUser();
        $this->loginUser();
        $tournament = $this->createTournament();

        // Test show the tournament as a guest
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->getJson("/api/tournament/{$tournament['id']}");

        $response->assertStatus(200);
    }



    /** @test */
    public function it_can_update_profile()
    {
        $this->registerUser();
        $token = $this->getToken();
        $user = User::first();

        $form = [
            'username' => 'abdou',
            'email' => 'abo@gmail.com',
        ];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->put("/api/profile/{$user->id}", $form);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_delete_profile()
    {
        $this->registerUser();
        $token = $this->getToken();
        $user = User::first();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->delete("/api/profile/{$user->id}");

        $response->assertStatus(204);
    }



    /** @test */
    public function it_can_show_profile()
    {
        $this->registerUser();
        $token = $this->getToken();
        $user = User::first();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->get("/api/profile/{$user->id}");

        $response->assertStatus(200);
    }


    /** @test */
    public function it_can_list_players()
    {
        $this->registerUser();
        $this->loginUser();
        $tournament = $this->createTournament();
        $token = $this->getToken();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->get("/api/tournament/{$tournament['id']}/players");

        $response->assertStatus(200);
    }


    /** @test */
    public function it_can_create_a_player()
    {
        $this->registerUser();
        $this->loginUser();
        $tournament = $this->createTournament();
        $token = $this->getToken();

        $user = User::first();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->post("/api/tournament/{$tournament['id']}/players", [
            'user_id' => $user->id,
        ]);

        $response->assertStatus(201);
    }



    /** @test */
    private function getPlayer()
    {
        $this->registerUser();
        $this->loginUser();
        $tournament = $this->createTournament();
        $token = $this->getToken();

        $user = User::first();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->post("/api/tournament/{$tournament['id']}/players", [
            'user_id' => $user->id,
        ]);

        return json_decode($response->getContent(), true);
    }

    /** @test */
    public function it_can_delete_a_player()
    {
        $player = $this->getPlayer();
        $token = $this->getToken();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->delete("/api/tournament/{$player['tournament_id']}/players/{$player['id']}");

        $response->assertStatus(200);
    }
}
