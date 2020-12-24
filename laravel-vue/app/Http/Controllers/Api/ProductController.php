<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductClientResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    //
    public function getAllProducts(){
        return ProductClientResource::collection(Product::all());
    }
}
