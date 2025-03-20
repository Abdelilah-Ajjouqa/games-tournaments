<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();

        return response()->json($posts, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validate = $request->validate([
                'title' => 'required|string|max:225|unique:posts',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'description' => 'nullable'
            ]);
    
            $post = Post::create([
                'title' => $validate['title'],
                'start_date' => $validate['start_date'],
                'end_date' => $validate['end_date'],
                'description' => $validate['description'] ?? null,
            ]);
    
            if(!$post){
                return response()->json(["message"=>"Post not created", "error"=>$post], 400);
            }
            
            return response()->json($post, 201);

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
            $post = Post::findOrFail($id);

            return response()->json($post, 200);
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
            $post = Post::findOrFail($id);

            $validate = $request->validate([
                'title'=>'sometimes|string|max:225|unique:posts',
                'start_date'=>'sometimes|date',
                'end_date'=>'sometimes|date',
                'description'=>'sometimes|nullable'
            ]);

            $post->update($validate);

            return response()->json($post, 200);
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
            $post = Post::findOrFail($id);

            $post->delete();

            return response()->json(null, 204);
        } catch(Exception $e){
            return response()->json(["message"=>"error", "error"=> $e->getMessage()], 404);
        }
    }
}
