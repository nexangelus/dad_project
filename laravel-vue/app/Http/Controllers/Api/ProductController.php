<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductClientResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    //
    public function getAllProducts(){
        return ProductClientResource::collection(Product::all());
    }

    public function getAllProductsWithDeleted(){
        return ProductClientResource::collection(Product::withTrashed()->get());
    }

    public function storeImage(Request $request) {
        if($request->file('file')) {
            $file = $request->file->store('/public/products');
            return asset('storage/products/'.basename($file));
        }

        return response()->json(["message" => "Error, no file was uploaded"], 403);
    }

    public function updateProduct(UpdateProductRequest $request, int $id) {
        /* @var Product $product */
        $product = Product::find($id);
        $product->fill($request->validated());
        $product->photo_url = basename($product->photo_url);
        $product->save();

        return new ProductClientResource(Product::find($id));
    }

    public function newProduct(StoreProductRequest $request) {
        $product = new Product();
        $product->fill($request->validated());
        $product->photo_url = basename($product->photo_url);
        $product->save();

        return new ProductClientResource(Product::find($product->id));
    }

    public function deleteProduct(int $id) {
        $product = Product::find($id);
        $product->delete();
    }


}
