<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    function ProductCreate(Request $request)
    {
        $user_id = $request->header('id');

        return Product::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'unit' => $request->input('unit'),
            'category_id' => $request->input('category_id'),
            'user_id' => $user_id
        ]);
    }
    function ProductList(Request $request)
    {
        $user_id = $request->header('id');

        return Product::where('user_id', $user_id)->get();
    }
    function ProductDelete(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('id');

        return Product::where('id', $product_id)->where('user_id', $user_id)->delete();
    }
    function ProductByID(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->query('id');

        return Product::where('id', $product_id)->where('user_id', $user_id)->first();
    }
    function ProductUpdate(Request $request)
    {

        try {
            $user_id = $request->header('id');
            $product_id = $request->input('id');

            $product = Product::where('id', $product_id)->where('user_id', $user_id)->first();

            $data = $request->only(['name', 'price', 'unit', 'category_id']);

            $product->fill($data)->save();

            return response()->json([
                'status' => "success",
                "message" => "Product updated successfully"
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
