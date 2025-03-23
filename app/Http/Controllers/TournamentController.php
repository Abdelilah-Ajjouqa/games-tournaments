<?php

namespace App\Http\Controllers;

use App\Models\tournament;
use Exception;
use Illuminate\Http\Request;

class tournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tournaments = tournament::all();

        return response()->json($tournaments, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validate = $request->validate([
                'title' => 'required|string|max:225|unique:tournaments',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'description' => 'nullable'
            ]);

            $tournament = tournament::create([
                'title' => $validate['title'],
                'start_date' => $validate['start_date'],
                'end_date' => $validate['end_date'],
                'description' => $validate['description'] ?? null,
            ]);

            if(!$tournament){
                return response()->json(["message"=>"tournament not created", "error"=>$tournament], 400);
            }

            return response()->json($tournament, 201);

        } catch(\Exception $e){
            return response()->json(["message"=>"error", "error"=> $e->getMessage()], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $tournament = tournament::findOrFail($id);

            return response()->json($tournament, 200);
        } catch(Exception $e){
            return response()->json(["message"=>"error", "error"=> $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $tournament = tournament::findOrFail($id);

            $request->validate([
                'title'=>'sometimes|string|max:225|unique:tournaments',
                'start_date'=>'sometimes|date',
                'end_date'=>'sometimes|date',
                'description'=>'sometimes|nullable'
            ]);

            $tournament->title = $request->title;
            $tournament->start_date = $request->start_date;
            $tournament->end_date = $request->end_date;
            $tournament->description = $request->description;

            $tournament->save();

            return response()->json($tournament, 200);
        } catch(Exception $e){
            return response()->json(["message"=>"error", "error"=> $e->getMessage()], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $tournament = tournament::findOrFail($id);

            $tournament->delete();

            return response()->json(null, 204);
        } catch(Exception $e){
            return response()->json(["message"=>"error", "error"=> $e->getMessage()], 404);
        }
    }
}
