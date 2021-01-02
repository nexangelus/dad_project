<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller {

    //TODO produtos pesquisas diarias tempo mes
    // pesquisas semanais tempo 6 mes
    // pesquisas mensais tempo 2 anos

    //TODO quando ganhou a empresa  pesquisas diarias tempo mes
    // pesquisas semanais tempo 6 mes
    // pesquisas mensais tempo 2 anos

    //TODO numero de orders diario, semanal,mensal

    //TODO Empregados avg por dia, mes dos pedidos
    //
    //TODO Tempo medio de entrega por dia, por mes
    //  Tempo medio de conzinhar por dia, por mes
    //

    public function getCookerStats($id){
        /* @var User $cooker */
        $cooker = User::withTrashed()->find($id);
        if($cooker != null && $cooker->type === 'EC'){
            $orders = Order::query()
                ->selectRaw('AVG(preparation_time) as average')
                ->selectRaw('CONCAT(YEAR(DATE),"-",LPAD(week(DATE),2, 0)) AS week')
                ->where(['status'=>'D', 'prepared_by'=> $cooker->id])
                ->whereRaw('date > DATE_SUB(CURDATE(), INTERVAL 2 Year)')
                ->whereRaw('date > DATE_SUB(CURDATE(), INTERVAL 6 Month)')
                ->groupBy( 'week')
                ->orderBy('week', 'ASC')
                ->get();
            return $orders;
        }
    }

    public function getDeliverersStats($id){
        /* @var User $cooker */
        $cooker = User::withTrashed()->find($id);
        if($cooker != null && $cooker->type === 'ED'){
            $orders = Order::query()
                ->selectRaw('AVG(preparation_time) as average')
                ->selectRaw('CONCAT(YEAR(DATE),"-",LPAD(week(DATE),2, 0)) AS week')
                ->where(['status'=>'D', 'delivered_by'=> $cooker->id])
                ->whereRaw('date > DATE_SUB(CURDATE(), INTERVAL 2 Year)')
                ->whereRaw('date > DATE_SUB(CURDATE(), INTERVAL 6 Month)')
                ->groupBy( 'week')
                ->orderBy('week', 'ASC')
                ->get();
            return $orders;
        }
    }





    //TODO feitas em baixo

    //region Global
    public function getGlobalEmployersStats() {
        $orders = Order::query()
            ->selectRaw('AVG(total_time) as average')
            ->selectRaw('CONCAT(YEAR(DATE),"-",LPAD(week(DATE),2, 0)) AS week')
            ->where(['status'=>'D'])
            ->whereRaw('date > DATE_SUB(CURDATE(), INTERVAL 2 Year)')
            ->whereRaw('date > DATE_SUB(CURDATE(), INTERVAL 6 Month)')
            ->groupBy( 'week')
            ->orderBy('week', 'ASC')
            ->get();
        return $orders;
    }

    public function getGlobalCookersStats(){
        $orders = Order::query()
            ->selectRaw('AVG(preparation_time) as average')
            ->selectRaw('CONCAT(YEAR(DATE),"-",LPAD(week(DATE),2, 0)) AS week')
            ->where(['status'=>'D'])
            ->whereRaw('date > DATE_SUB(CURDATE(), INTERVAL 2 Year)')
            ->whereRaw('date > DATE_SUB(CURDATE(), INTERVAL 6 Month)')
            ->groupBy( 'week')
            ->orderBy('week', 'ASC')
            ->get();
        return $orders;
    }

    public function getGlobalDeliverersStats(){
        $orders = Order::query()
            ->selectRaw('AVG(delivery_time) as average')
            ->selectRaw('CONCAT(YEAR(DATE),"-",LPAD(week(DATE),2, 0)) AS week')
            ->where(['status'=>'D'])
            ->whereRaw('date > DATE_SUB(CURDATE(), INTERVAL 2 Year)')
            ->whereRaw('date > DATE_SUB(CURDATE(), INTERVAL 6 Month)')
            ->groupBy( 'week')
            ->orderBy('week', 'ASC')
            ->get();
        return $orders;
    }
    //endregion

    //region Last 2 Years
    public function getLast2YearsMonthsAllProductsSales(){
        $orders = Order::query()
            ->selectRaw('CONCAT(YEAR(DATE),"-",LPAD(MONTH(DATE),2, 0)) AS month')
            ->selectRaw('SUM(order_items.sub_total_price) as earn')
            ->selectRaw('SUM(order_items.quantity) as sum')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'product_id', '=', 'products.id')
            ->where(['status'=>'D'])
            ->whereRaw('date > DATE_SUB(CURDATE(), INTERVAL 2 Year)')
            ->groupBy( 'month')
            ->orderBy('month', 'ASC')
            ->get();
        return $orders;
    }

    public function getLast2YearsMonthsProductSales($productID){
        $findProduct = Product::withTrashed()->find($productID);
        if ($findProduct!=null){
            $orders = Order::query()
                ->select('name')
                ->selectRaw('CONCAT(YEAR(DATE),"-",LPAD(MONTH(DATE),2, 0)) AS month')
                ->selectRaw('SUM(order_items.sub_total_price) as earn')
                ->selectRaw('SUM(order_items.quantity) as sum')
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'product_id', '=', 'products.id')
                ->where(['product_id' => $findProduct->id, 'status'=>'D'])
                ->whereRaw('date > DATE_SUB(CURDATE(), INTERVAL 2 Year)')
                ->groupBy( 'month', 'product_id')
                ->orderBy('month', 'ASC')
                ->get();
            return $orders;
        }
        return null;
    }
    //endregion
    //region Last 6 Months
    public function getLast6MonthsAllProductsSales(){
        $orders = Order::query()
            ->selectRaw('CONCAT(YEAR(DATE),"-",LPAD(week(DATE)+1,2, 0)) AS week')
            ->selectRaw('SUM(order_items.sub_total_price) as earn')
            ->selectRaw('SUM(order_items.quantity) as sum')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'product_id', '=', 'products.id')
            ->where(['status'=>'D'])
            ->whereRaw('date > DATE_SUB(CURDATE(), INTERVAL 6 Month)')
            ->groupBy( 'week')
            ->orderBy('week', 'ASC')
            ->get();
        return $orders;
    }

    public function getLast6MonthsProductSales($productID){
        $findProduct = Product::withTrashed()->find($productID);
        if ($findProduct!=null){
            $orders = Order::query()
                ->select('name')
                ->selectRaw('CONCAT(YEAR(DATE),"-",LPAD(week(DATE)+1,2, 0)) AS week')
                ->selectRaw('SUM(order_items.sub_total_price) as earn')
                ->selectRaw('SUM(order_items.quantity) as sum')
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'product_id', '=', 'products.id')
                ->where(['product_id' => $findProduct->id, 'status'=>'D'])
                ->whereRaw('date > DATE_SUB(CURDATE(), INTERVAL 6 Month)')
                ->groupBy( 'week', 'product_id')
                ->orderBy('week', 'ASC')
                ->get();
            return $orders;
        }
        return null;
    }
    //endregion
    //region Current
    public function getCurrentMonthProductSales($id){
        $findProduct = Product::withTrashed()->find($id);

        if ($findProduct!=null){
            $orders = Order::query()
                ->select('name')
                ->selectRaw('DAYOFMONTH(date) as day')
                ->selectRaw('SUM(order_items.sub_total_price) as earn')
                ->selectRaw('SUM(order_items.quantity) as sum')
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'product_id', '=', 'products.id')
                ->where(['product_id' => $findProduct->id, 'status' => 'D'])
                ->whereRaw('MONTH(CURDATE()) = MONTH(date)')
                ->groupBy('product_id', 'day')
                ->orderBy('day', 'ASC')
                ->get();
        }
        return $orders;
    }

    public function getCurrentMonthAllProductsSales(){
        $orders = Order::query()
            ->selectRaw('DAYOFMONTH(date) as day')
            ->selectRaw('SUM(order_items.sub_total_price) as earn')
            ->selectRaw('SUM(order_items.quantity) as sum')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'product_id', '=', 'products.id')
            ->where(['status' => 'D'])
            ->whereRaw('MONTH(CURDATE()) = MONTH(date)')
            ->groupBy( 'day')
            ->orderBy('day', 'ASC')
            ->get();

        return $orders;
    }
    //endregion
    //region LastMonth
    public function getLastMonthProductSales($productID){
        $findProduct = Product::withTrashed()->find($productID);
        if ($findProduct!=null){
            $orders = Order::query()
                ->select('name')
                ->selectRaw('DAYOFMONTH(date) as day')
                ->selectRaw('SUM(order_items.sub_total_price) as earn')
                ->selectRaw('SUM(order_items.quantity) as sum')
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'product_id', '=', 'products.id')
                ->where(['product_id' => $findProduct->id, 'status'=>'D'])
                ->whereRaw('Month(date) = Month(DATE_SUB(CURDATE(), INTERVAL 1 Month))')
                ->groupBy( 'day', 'product_id')
                ->orderBy('day', 'ASC')
                ->get();
            return $orders;
        }
        return null;
    }

    public function getLastMonthAllProductsSales(){
        $orders = Order::query()
            ->selectRaw('DAYOFMONTH(date) as day')
            ->selectRaw('SUM(order_items.quantity) as sum')
            ->selectRaw('SUM(order_items.sub_total_price) as earn')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'product_id', '=', 'products.id')
            ->where(['status'=>'D'])
            ->whereRaw('Month(date) = Month(DATE_SUB(CURDATE(), INTERVAL 1 Month))')
            ->groupBy( 'day')
            ->orderBy('day', 'ASC')
            ->get();

        return $orders;
    }
    //endregion
}
