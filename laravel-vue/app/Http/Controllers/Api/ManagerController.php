<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeesForManagerResource;
use App\Http\Resources\OrderForManagerResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class ManagerController extends Controller {

    /**
     * CENAS QUE O MANAGER PODE FAZER:
     * - supervisionar os cooks e deliverymen
     * - mostrar na dashboard a lista de todos os empregados e o estado deles (se estão a trabalhar e em quê)
     * - mostrar a lista das ordens ativas (que não foi entregue ou cancelada) com um resumo de cada (id, status, quem está a tratar)
     * - um manager pode cancelar uma ordem que não foi entregue
     * - tem acesso a mais informações acerca da empresa (QUAIS?)
     * - gerir produtos (CRUD)
     * - bloquear utilizadores (exceto ele mesmo)
     * - criar/atualizar/apagar empregados
     * - apagar um cliente
     */

    public function getDashboardData() {
        $orders = Order::query()->whereIn("status", ['H', 'P', 'R', 'T'])->get();
        $employees = User::query()->whereIn("type", ["EM", "ED", "EC"])->whereNotNull("logged_at")->get();

        return ["orders" => OrderForManagerResource::collection($orders), "employees" => EmployeesForManagerResource::collection($employees)];
    }

    public function getCooksWorking() {
        /* @var User[] $cooksLoggedIn */
        /* @var Order $order */
        $cooksLoggedIn = User::query()->where(["type" => "EC"])->whereNotNull('logged_at')->get();

        $cooksStatus = [];

        foreach ($cooksLoggedIn as $cook) {
            // SELECT * FROM orders WHERE orders.prepared_by = 6 ORDER BY id DESC LIMIT 1
            $order = Order::query()->where(['prepared_by' => $cook->id])->orderBy('id', "desc")->limit(1)->first();
            if($order != null && $order->status == 'P') {
                $cooksStatus[$cook->id] = "busy";
            } else {
                $cooksStatus[$cook->id] = "wait";
            }
        }

        return $cooksStatus;
    }

    public function getOrderCookIsWorkingOn($id) {
        $order = Order::query()->where(['prepared_by' => $id, "status" => "P"])->first();
        if($order) {
            return new OrderResource($order);
        }
        return null;
    }

    public function getOrderDeliverymanIsWorkingOn($id) {
        $order = Order::query()->where(['delivered_by' => $id, "status" => "T"])->first();
        if($order) {
            return new OrderResource($order);
        }
        return null;
    }

    public function cancelOrder($id){
        /* @var Order $order */
        $order = Order::find($id);
        if($order != null){
            $order->status = 'C';
            $time = new \DateTime();
            $order->current_status_at = $time;
            $order->save();
        }
        return null;
    }

    public function blockUser(Request $request, $id, string $status) {
        /* @var User $manager */ /* @var User $user */
        $manager = $request->user();
        if($manager->id == $id) {
            return response()->json(['message' => "You can't block yourself"], 403);
        }

        $block = -1;
        switch ($status) {
            case 'block':
            case 'blocked':
                $block = 1;
                break;
            case 'unblock':
            case 'unblocked':
                $block = 0;
                break;
        }

        if($block == -1) {
            return response()->json(['message' => "The status '$status' was not recognized"], 403);
        }

        $user = User::find($id);

        $user->blocked = $block;
        $user->available_at = null;
        $user->logged_at = null;
        $user->save();

        return new UserResource(User::find($user->id));
    }
}
