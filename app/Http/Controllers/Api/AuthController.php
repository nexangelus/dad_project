<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if($user['blocked']) {
                return response()->json(['message' => 'Forbidden: Account is blocked.'], 403);
            }
            return Auth::user();
        } else {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }
    }

    public function logout() {
        Auth::guard('web')->logout(); //check if Auth::logout(); works
        return response()->json(['msg' => 'User session closed'], 200);
    }
}
