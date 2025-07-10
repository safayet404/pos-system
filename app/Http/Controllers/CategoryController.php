<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CategoryController extends Controller
{

    function CategoryPage(Request $request)
    {
        $user_id = $request->header('id');
        $list = Category::with('user:id,name')->where('user_id', $user_id)->get();
        return Inertia::render('CategoryPage', ['list' => $list]);
    }
    function CategorySavePage(Request $request)
    {
        $user_id = $request->header('id');

        $category_id = $request->query('id');
        if ($category_id != 0) {
            $list = Category::where('id', $category_id)->where('user_id', $user_id)->first();
            return Inertia::render('CategorySavePage', ['list' => $list]);
        } else {

            return Inertia::render('CategorySavePage');
        }
    }
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

        Category::create([
            'name' => $validated['name'],
            'user_id' => $user_id
        ]);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    function CategoryDelete(Request $request)
    {

        try{
            $category_id = $request->id;
        $user_id = $request->header('id');

        $category = Category::where('id', $category_id)->where('user_id', $user_id)->first();

        if ($category) {
            $category->delete();
            return Redirect::back()->with([
                'status' => true,
                'message' => 'Category deleted successfully!'
            ]);
        } else {
            return Redirect::back()->with([
                'status' => false,
                'message' => 'This category does not exist in the system!'
            ]);
        }
        }catch(Exception $e)
        {
            return redirect()->back()->withErrors("Something went wrong");
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

            return redirect()->back()->with('success', 'Category Updated successfully.');
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
