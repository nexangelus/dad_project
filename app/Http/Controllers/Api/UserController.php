<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller {

    public function test() {
        return "very cool";
    }

    public function me(Request $request) {
        return $request->user();
        // Alternative: return Auth::user();
    }
}
