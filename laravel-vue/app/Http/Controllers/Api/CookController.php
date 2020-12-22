<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cook;
use App\Models\Order;
use Illuminate\Http\Request;

class CookController extends Controller {
    public function getWorkToDo(Request $request) {

        $user = $request->user();
        $amPreparing = Order::query()->where(['prepared_by' => $user->id, 'status' => 'P'])->first();
        if ($amPreparing == null) {
            return $this->checkWorkAndAssign($user->id);
        }
        return $amPreparing;
    }

    public function setOrderReady(Request $request) {
        $user = $request->user();
        $amPreparing = Order::query()->where(['prepared_by' => $user->id, 'status' => 'P'])->first();
        if ($amPreparing != null) {
            $amPreparing->status = 'R';
            $timeCookingStarted = $amPreparing->current_status_at;
            $timeNow = new \DateTime();
            $timeSpent = $timeNow - $timeCookingStarted;
            $amPreparing->current_status_at = $timeNow;
            $amPreparing->preparation_time = $timeSpent;
            $amPreparing->save();
        }
    }

    private function checkWorkAndAssign($userId) {
        $orderToDo = Order::query()->where(['status' => 'H'])->orderBy('created_at', 'ASC')->first();
        if ($orderToDo != null) {
            $orderToDo->prepared_by = $userId;
            $orderToDo->status = 'P';
            //TODO resto do update
            $orderToDo->save();
        }
        return $orderToDo;
    }


}
