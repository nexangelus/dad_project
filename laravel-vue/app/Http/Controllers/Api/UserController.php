<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Utils\SocketIO;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Env;

class UserController extends Controller {

    public function test() {
        $a = User::find(5);
        return new UserResource($a);
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
        return new UserResource($request->user());
    }

    public function changePassword(Request $request) {
        $user = $request->user();
        $error = ["message" => "The given data was invalid", "errors" => []];
        if(!Hash::check($request->old, $user->password)) {
            $error["errors"]["old"] = ["The old password is incorrect."];
        } else if($request->new1 != $request->new2) {
            $error["errors"]["new"] = ["The new passwords do not match."];
        } else if (strlen($request->new1) < 3) {
            $error["errors"]["new"] = ["The password must be at least 3 characters."];
        } else {
            $user->password = bcrypt($request->new1);
            $user->save();
            return ["status" => "OK"];
        }

        return response($error, 422);

    }

    public function update(UpdateUserRequest $request) {
        $user = $request->user();
        $user->fill($request->validated());
        $user->save();

        if($user->type == "C") {
            $customer = Customer::find($user->id);
            $customer->fill($request->validated());
            $customer->save();
        }

        return new UserResource($request->user());
    }

    public function notifyNewSocketID(Request $request) {
        $validated = $request->validate([
            'socketID' => 'required'
        ]);

        $socketID = $request->socketID;

        SocketIO::notifySocketIDChanged($request->user(), $socketID);

        return ["status" => "OK"];
    }
}
