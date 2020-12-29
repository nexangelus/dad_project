<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeesForManagerResource;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\User;
use App\Utils\SocketIO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    public function login(Request $request) {
        /* @var User $user*/
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if($user['blocked']) {
                return response()->json(['message' => 'Forbidden: Account is blocked.'], 403);
            }

            // se for um cook ou deliveryman então vai ser preciso verificar se eles têm pedidos para não atualizar a data de available at
            if($user->type === "EC" || $user->type === "ED") {
                $query = null;
                if($user->type === "EC") {
                    $query = ['prepared_by' => $user->id, 'status' => 'P'];
                } else {
                    $query = ['delivered_by' => $user->id, 'status' => 'T'];
                }
                // buscar o primeiro pedido preparado/a entregar pelo employee
                $order = $amPreparing = Order::query()->where($query)->first();
                if(!$order) { // se não houver definir o available_at
                    $user->available_at = new \DateTime();
                }
            }

            $user->logged_at = new \DateTime();
            $user->save();

            $user = User::find($user->id);

            SocketIO::notifyLogin($user, $request->socketID);

            return new UserResource($user);
        } else {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }
    }

    public function logout(Request $request) {
        /* @var User $user*/
        $user = $request->user();
        SocketIO::notifyLogout($user);

        $user->logged_at = null;
        $user->available_at = null;
        $user->save();

        // TODO Perguntar como é que isto é suposto funcionar a 100%. Se eu importar o pedido para o postman e tentar
        //  fazer um pedido após fazer logout ainda deixa fazer pedidos, se eu fizer login novamente também permite
        //  ainda fazer pedidos com o token antigo.


        Auth::guard('web')->logout(); //check if Auth::logout(); works
        return response()->json(['msg' => 'User session closed'], 200);
    }
}
