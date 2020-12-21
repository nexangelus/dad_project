<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller {

    public function test() {
        return User::all();
    }

    public function register(StoreUserRequest $request) {
        $user = new User();
        $user->fill($request->validated());
        $user->password = bcrypt($user->password);
        $user->save();

        $customer = new Customer();
        $customer->fill($request->validated());
        $customer->id = $user->id;
        $customer->save();

        return response()->json($user, 201);
    }

    public function uploadPhoto(Request $request) {
        $user = $request->user();

        if($request->file('file')) {
            $file = $request->file->store('/public/fotos');
            $user->photo_url = basename($file);
            $user->save();
        }
    }

    public function me(Request $request) {
        return $request->user();
        // Alternative: return Auth::user();
    }
}
