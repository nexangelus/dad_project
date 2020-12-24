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


    ////////////
    private static function postHTTP($endpoint, $data) {
        $url = config('app.websocketUrl') . "" . $endpoint;
        Http::post($url, $data);
    }

}
