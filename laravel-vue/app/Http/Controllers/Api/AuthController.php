<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\User;
use App\Utils\SocketIO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function login(Request $request) {
        /* @var User $user*/
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if($user['blocked']) {
                return response()->json(['message' => 'Forbidden: Account is blocked.'], 403);
            }

            if($user->type === "EC") {
                $order = $amPreparing = Order::query()->where(['prepared_by' => $user->id, 'status' => 'P'])->first();
                if(!$order) {
                    $user->available_at = new \DateTime();
                }
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
        /* @var User $user*/
        $user = $request->user();
        SocketIO::notifyLogout($user);

        $user->logged_at = null;
        $user->available_at = null;
        $user->save();

        Auth::guard('web')->logout(); //check if Auth::logout(); works
        return response()->json(['msg' => 'User session closed'], 200);
    }
}
