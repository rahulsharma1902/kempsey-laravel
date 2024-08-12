<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use Hash;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        return response()->json($request->all());
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json(['message' => 'Logout successful'], 200);
    }
}
