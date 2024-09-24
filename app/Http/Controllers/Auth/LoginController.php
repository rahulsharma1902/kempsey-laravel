<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        if($request->tempId){
            $this->convertTemporaryIdToUserId($request->tempId);
        }
        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }
    function convertTemporaryIdToUserId($tempId)
    {
        $temporaryUserId = $tempId;
        if ($temporaryUserId) {
            Cart::where('temp_id', $temporaryUserId)->update(['user_id' => Auth::id(), 'temp_id' => null]);
        }
    }
}
