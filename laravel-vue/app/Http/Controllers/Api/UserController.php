<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\OrderFilter;
use App\Http\Filters\UserFilter;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Resources\OrderForManagerResource;
use App\Http\Resources\UserEmployerResource;
use App\Models\Customer;
use App\Models\Order;
use App\Utils\SocketIO;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Env;

class UserController extends Controller {

    public function test() {
        $a = User::find(5);
        return new UserResource($a);
    }

    public function register(StoreCustomerRequest $request) {
        $user = new User();
        $user->fill($request->validated());
        $user->password = bcrypt($user->password);
        $user->save();

        $customer = new Customer();
        $customer->fill($request->validated());
        $customer->id = $user->id;
        $customer->save();

        return response()->json($user, 201);
    }

    public function uploadPhoto(Request $request) {
        $user = $request->user();

        if($request->file('file')) {
            $file = $request->file->store('/public/fotos');
            $user->photo_url = basename($file);
            $user->save();

            return asset('storage/fotos/'.$user->photo_url);
        }

        return response()->json(["message" => "Error, no file was uploaded"], 403);
    }

    public function me(Request $request) {
        /* @var User $user */
        $user = $request->user();
        if($user->logged_at == null) {
            $user->logged_at = new \DateTime();

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

            $user->save();

            $user = User::find($user->id);
        }
        return new UserResource($request->user());
    }

    public function changePassword(Request $request) {
        $user = $request->user();
        $error = ["message" => "The given data was invalid", "errors" => []];
        if(!Hash::check($request->old, $user->password)) {
            $error["errors"]["old"] = ["The old password is incorrect."];
        } else if($request->new1 != $request->new2) {
            $error["errors"]["new"] = ["The new passwords do not match."];
        } else if (strlen($request->new1) < 3) {
            $error["errors"]["new"] = ["The password must be at least 3 characters."];
        } else {
            $user->password = bcrypt($request->new1);
            $user->save();
            return ["status" => "OK"];
        }

        return response($error, 422);

    }

    public function update(UpdateUserRequest $request) {
        $user = $request->user(); // TODO user id must be the same
        $user->fill($request->validated());
        $user->save();

        if($user->type == "C") {
            $customer = Customer::find($user->id);
            $customer->fill($request->validated());
            $customer->save();
        }

        return new UserResource($request->user());
    }

    public function notifyNewSocketID(Request $request) {
        $request->validate([
            'socketID' => 'required'
        ]);

        $socketID = $request->socketID;

        SocketIO::notifySocketIDChanged($request->user(), $socketID);

        return ["status" => "OK"];
    }

    public function getUser(Request $request, int $id) {
        $user = User::find($id);
        return new UserResource($user);
    }

    public function getUserEmployers(){
        $users = array();
        $users['cookers'] = UserEmployerResource::collection(User::withTrashed()->where(['type' => 'EC'])->get());
        $users['deliverers'] = UserEmployerResource::collection(User::withTrashed()->where(['type' => 'ED'])->get());
        return $users;
    }

    public function getAll(UserFilter $filter) {
        $users = User::filter($filter)->paginate(10)->withQueryString()->onEachSide(0);
        return UserResource::collection($users);
    }

    public function saveNewPhotoForUser(Request $request, int $id) {
        if($request->file('file')) {
            $file = $request->file->store('/public/fotos');
            $user = User::find($id);
            $user->photo_url = basename($file);
            $user->save();

            return asset('storage/fotos/'.$user->photo_url);
        }

        return response()->json(["message" => "Error, no file was uploaded"], 403);
    }

    public function newEmployee(StoreEmployeeRequest $request) {
        $user = new User();
        $user->fill($request->validated());
        $user->password = bcrypt($user->password);
        $user->save();

        $user = User::find($user->id);

        return new UserResource($user);
    }

    public function updateUser(UpdateUserRequest $request, int $id) {
        $user = User::find($id);
        $user->fill($request->validated());
        $user->save();

        return new UserResource(User::find($id));
    }

    public function deleteUser(Request $request, int $id) {
        /* @var User $user */
        if($request->user()->id != $id) {
            $user = User::find($id);
            if($user->type == "C") {
                $user->customer->delete();
            }
            $user->delete();
        }

        return new UserResource($user);
    }

    public function getUserOrders(OrderFilter $filter){
        /* @var Order $orders */
        $orders = Order::filter($filter)->paginate(10)->withQueryString()->onEachSide(0);
        return OrderForManagerResource::collection($orders);

    }

}
