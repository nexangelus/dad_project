<?php

namespace App\Listeners;

use App\Models\User;
use App\Utils\SocketIO;
use Illuminate\Support\Facades\Log;

class UserSaveListener {

    /* @var User $user */
    public function __construct(User $user) {

        if($user->blocked == 1 && $user->getOriginal('blocked') == 0) { // action was a lock by the manager, specifically notify that the user was blocked
            SocketIO::notifyUserBlocked($user->id);
        }
    }

}
