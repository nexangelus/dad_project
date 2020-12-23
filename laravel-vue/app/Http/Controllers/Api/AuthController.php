<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use App\Models\User;
use App\Utils\SocketIO;
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

            $user->logged_at = new \DateTime();
            $user->save();
            SocketIO::notifyLogin($user, $request->socketID);
            return new UserResource($user);
        } else {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }
    }

    public function logout(Request $request) {
        SocketIO::notifyLogout($request->user());

        $request->user()->logged_at = null;
        $request->user()->save();

        Auth::guard('web')->logout(); //check if Auth::logout(); works
        return response()->json(['msg' => 'User session closed'], 200);
    }
}
