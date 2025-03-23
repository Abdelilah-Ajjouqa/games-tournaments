<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\tournament;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index($id)
    {
        try{
            $tournament = tournament::findOrFail($id);
            $players = $tournament->players;
            return response()->json($players, 200);
        } catch(\Exception $e){
            return response()->json(["message"=>"error", "error"=> $e->getMessage()], 404);
        }
    }

    public function store(Request $request, $id)
    {
        try{
            $validate = $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $player = Player::create([
                'user_id' => $validate['user_id'],
                'tournament_id' => $id,
            ]);

            if(!$player){
                return response()->json(["message"=>"player not created", "error"=>$player], 400);
            }

            return response()->json($player, 201);

        } catch(\Exception $e){
            return response()->json(["message"=>"error", "error"=> $e->getMessage()], 404);
        }
    }

    public function destroy(string $id)
    {
        try{
            $player = Player::findOrFail($id);
            $player->delete();

            return response()->json(["message"=>"player deleted"], 200);
        } catch(\Exception $e){
            return response()->json(["message"=>"error", "error"=> $e->getMessage()], 404);
        }
    }
}
