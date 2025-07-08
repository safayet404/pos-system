<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProductController extends Controller
{

    function ProductPage(Request $request)
    {

        $user_id = $request->header('id');

        $list =  Product::with('category')->where('user_id', $user_id)->get();
        return Inertia::render('ProductPage', ['list' => $list]);
    }
    function ProductCreate(Request $request)
    {
        try {
            $user_id = $request->header('id');

            $validated = $request->validate([
                'name' => 'required|string|max:50',
                'price' => 'required|numeric|min:0',
                'unit' => 'required|string|max:20',
                'category_id' => 'required|integer|exists:categories,id'
            ]);

            return Product::create([
                'name' => $validated['name'],
                'price' =>  $validated['price'],
                'unit' => $validated['unit'],
                'category_id' => $validated['category_id'],
                'user_id' => $user_id
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Product creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    function ProductList(Request $request)
    {
        $user_id = $request->header('id');

        return Product::with('category')->where('user_id', $user_id)->get();
    }
    function ProductDelete(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->id;

        $category = Product::where('id', $product_id)->where('user_id', $user_id)->first();

        if ($category) {
            $category->delete();

            return Redirect::back()->with([
                'status' => true,
                'message' => 'Product deleted successfully'
            ]);
        } else {
            return response()->json(['status' => 'failed', 'message' => "This Product is not exist in the system"]);
        }
    }
    function ProductByID(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('id');

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
