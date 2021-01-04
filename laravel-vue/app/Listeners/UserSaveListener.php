<?php

namespace App\Listeners;

use App\Http\Resources\EmployeesForManagerResource;
use App\Models\User;
use App\Utils\SocketIO;
use Illuminate\Support\Facades\Log;

class UserSaveListener {

    /* @var User $user */
    public function __construct(User $user) {

        $newUser = User::find($user->id);

        // action was a lock by the manager, specifically notify that the user was blocked
        if ($user->blocked == 1 && $user->getOriginal('blocked') == 0) {
            SocketIO::notifyUserBlocked($user->id);
        }

        // user has logged in/out, need to notify managers that the employee has logged in
        if ($user->logged_at != null && $user->getOriginal('logged_at') == null && $user->type != "C") {
            SocketIO::notifyUpdatedEmployeeForManagers(new EmployeesForManagerResource($newUser)); // login
        } else if ($user->logged_at == null && $user->getOriginal('logged_at') != null && $user->type != "C") {
            SocketIO::notifyUpdatedEmployeeForManagers(["id" => $user->id, "__remove" => true]); // logout
        }

        if ($user->available_at != null && $user->getOriginal('available_at') == null && $user->type != "C") {
            SocketIO::notifyUpdatedEmployeeForManagers(new EmployeesForManagerResource($newUser));
        } else if ($user->available_at == null && $user->getOriginal('available_at') != null && $user->type != "C") {
            SocketIO::notifyUpdatedEmployeeForManagers(new EmployeesForManagerResource($newUser));
        }
    }

}
