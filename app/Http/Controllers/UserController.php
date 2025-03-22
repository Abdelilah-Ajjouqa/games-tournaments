<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $validate = $request->validate([
                'first_name' => 'sometimes|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                // 'email' => 'sometimes|string|email|max:255|unique:users,email,'.$id,
                'username' => 'sometimes|string|max:255|unique:users',
            ]);
            $user->update($validate);
            return response()->json(["message" => "You have updated your profile successfully", "user" => $user], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'User not updated', 'error' => $e->getMessage()], 400);
        }
    }

    public function destroy(string $id)
    {
        try{
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(null, 204);
        } catch (Exception $e){
            return response()->json(["message"=>"error", "error"=>$e->getMessage()], 404);
        }
    }
}
