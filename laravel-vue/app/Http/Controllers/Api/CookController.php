<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;

class CookController extends Controller {

    public function getWorkToDo(Request $request) {

        $user = $request->user();
        $amPreparing = Order::query()->where(['prepared_by' => $user->id, 'status' => 'P'])->first();
        if ($amPreparing == null) {

            return $this->checkWorkAndAssign($user->id);
        }
        return new OrderResource($amPreparing);
    }

    public function setOrderReady(Request $request) {
        /* @var Order $amPreparing*/
        $user = $request->user();
        $amPreparing = Order::query()->where(['prepared_by' => $user->id, 'status' => 'P'])->first();
        if ($amPreparing != null) {
            $amPreparing->status = 'R';
            $timeCookingStarted = new \DateTime($amPreparing->current_status_at);
            $timeNow = new \DateTime();
            $timeSpent = $timeNow->getTimestamp() - $timeCookingStarted->getTimestamp();
            $amPreparing->current_status_at = $timeNow;
            $amPreparing->preparation_time = $timeSpent;
            $amPreparing->save();
        }
    }

    private function checkWorkAndAssign($userId) {
        /* @var Order $orderToDo*/
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
