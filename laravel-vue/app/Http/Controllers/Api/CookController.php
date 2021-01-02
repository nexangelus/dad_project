<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeesForManagerResource;
use App\Http\Resources\OrderForCustomerResource;
use App\Http\Resources\OrderForManagerResource;
use App\Models\Order;
use App\Models\User;
use App\Utils\SocketIO;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Log;

class CookController extends Controller {

    public function getWorkToDo(Request $request) {

        $user = $request->user();

        $amPreparing = Order::query()->where(['prepared_by' => $user->id, 'status' => 'P'])->first();

        if ($amPreparing == null) {
            return $this->checkWorkAndAssign($user);
        }
        return new OrderResource($amPreparing);
    }

    public function setOrderReady(Request $request) {
        /* @var Order $amPreparing*/ /* @var User $user */
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

            $user->available_at = new \DateTime();
            $user->save();
        }
    }

    private function checkWorkAndAssign($user) {
        /* @var Order $orderToDo*/
        $orderToDo = Order::query()->where(['status' => 'H'])->orderBy('created_at', 'ASC')->first();
        if ($orderToDo != null) {
            $orderToDo->prepared_by = $user->id;
            $orderToDo->status = 'P';
            $orderToDo->current_status_at = $timeNow = new \DateTime();
            $orderToDo->save();
            $user->available_at = null;
            $user->save();
            $savedOrder = Order::find($orderToDo->id);
            return new OrderResource($savedOrder);
        }
        return null;
    }


}
