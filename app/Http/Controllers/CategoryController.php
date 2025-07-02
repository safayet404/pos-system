<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function CategoryList(Request $request)
    {
        $user_id = $request->header('id');
        return Category::with('user:id,name')->where('user_id', $user_id)->get();
    }
    function CategoryCreate(Request $request)
    {
        $user_id = $request->header('id');
        $validated = $request->validate([
            'name' => 'required|string|max:50'
        ]);

        return Category::create([
            'name' => $validated['name'],
            'user_id' => $user_id
        ]);
    }
    function CategoryDelete(Request $request)
    {
        $category_id = $request->input('id');
        $user_id = $request->header('id');

        $category = Category::where('id', $category_id)->where('user_id', $user_id)->first();

        if ($category) {
            return Category::where('id', $category_id)->where('user_id', $user_id)->delete();
        } else {
            return response()->json(['status' => 'failed', 'message' => "This category is not exist in the system"]);
        }
    }
    function CategoryByID(Request $request)
    {
        try {
            $category_id = $request->input('id');
            $user_id = $request->header('id');

            $category = Category::where('id', $category_id)
                ->where('user_id', $user_id)
                ->first();

            if ($category) {
                return response()->json($category, 200);
            } else {
                return response()->json(['message' => 'Category not found'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['message' =>  $e->getMessage()], 500);
        }
    }

    function CategoryUpdate(Request $request)
    {

        try {
            $category_id = $request->input('id');
            $user_id = $request->header('id');

            $category = Category::where('id', $category_id)->where('user_id', $user_id)->first();

            $data = $request->only(['name']);

            $category->fill($data)->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
