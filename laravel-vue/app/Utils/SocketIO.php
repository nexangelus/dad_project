<?php


namespace App\Utils;

use Illuminate\Support\Facades\Http;

class SocketIO {

    public static function notifyLogin($user, $socketID) {
        self::postHTTP("login", ["user" => $user, "socketID" => $socketID]);
    }

    public static function notifyLogout($user) {
        self::postHTTP("logout", ["user" => $user]);
    }

    public static function notifySocketIDChanged($user, $socketID) {
        self::postHTTP('idChanged', ["user" => $user, "socketID" => $socketID]);
    }

    public static function notifyNewOrder($cookID) {
        self::postHTTP('newOrderForCustomer', ['cookID' => $cookID]);
    }

    public static function notifyUpdateOrdersTableManager($order) {
        self::postHTTP('updateOrdersTableManager', ['order' => $order]);
    }

    public static function notifyUpdatedEmployeeForManagers($user) {
        self::postHTTP('updatedEmployeeForManagers', ['user' => $user]);
    }

    public static function notifyUpdatedOrder($order) {
        self::postHTTP('updateCustomerOrder', ['order' => $order]);
    }

    public static function notifyUserBlocked($id) {
        self::postHTTP('userBlocked', ['userID' => $id]);
    }

    public static function notifyOrderInTransitToOtherDelivery(int $id) {
        self::postHTTP('orderInTransitForDeliverers', ['orderID' => $id]);
    }

    public static function notifyDeliverymansNewOrder($order) {
        self::postHTTP('newOrderForDelivery', ['order' => $order]);
    }

    public static function notifyOrderCancelled(int $idWorkingOn, int $orderID) {
        self::postHTTP('orderCancelled', ['userID' => $idWorkingOn, 'orderID' => $orderID]);
    }

    ////////////
    private static function postHTTP($endpoint, $data) {
        $url = config('app.websocketUrl') . "" . $endpoint;
        Http::post($url, array_merge(["pw" => config('app.websocketPW')], $data));
    }



}
