<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CooksForManagerResource;
use App\Http\Resources\OrderForManagerResource;
use App\Http\Resources\OrderResource;
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

    // TODO apenas o manager pode aceder a isto
    public function getDashboardData(Request $request) {
        /* @var User[] $cooks */

        $orders = Order::query();
        $cooks = User::query()->where(["type" => "EC"]);
        $delivery = User::query()->where(["type" => "ED"]);

        if($request->query('all', true)){
            $orders = $orders->whereIn("status", ['H', 'P', 'R', 'T']);
            $cooks = $cooks->whereNotNull("logged_at");
            $delivery = $delivery->whereNotNull("logged_at");
        }

        $orders = $orders->get();
        $cooks = $cooks->get();
        $delivery = $delivery->get();

        foreach ($cooks as $i => $cook) {
            if($cook->logged_at != null) {
                $order = Order::query()->where(['prepared_by' => $cook->id])->orderBy('id', "desc")->limit(1)->first();
                if($order != null && $order->status == 'P') {
                    $cooks[$i]['status'] = "busy";
                } else {
                    $cooks[$i]['status'] = "wait";
                }
            }
        }


        return ["orders" => OrderForManagerResource::collection($orders), "cooks" => CooksForManagerResource::collection($cooks), "delivery" => $delivery];
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
        return new OrderResource($order);
    }
}
