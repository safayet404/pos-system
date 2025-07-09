<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CustomerController extends Controller
{

    function CustomerPage(Request $request)
    {
        $user_id = $request->header('id');
        $list = Customer::where('user_id', $user_id)->get();
        return Inertia::render('CustomerPage', ['list' => $list]);
    }
    function CustomerSave(Request $request)
    {
        $user_id = $request->header('id');

        $customer_id = $request->query('id');
        if ($customer_id != 0) {
            $list = Customer::where('id', $customer_id)->where('user_id', $user_id)->first();
            return Inertia::render('CustomerSavePage', ['list' => $list]);
        } else {

            return Inertia::render('CustomerSavePage');
        }
    }


    public function CustomerCreate(Request $request)
    {
        try {
            $user_id = $request->header('id');

            $validated = $request->validate([
                'name' => 'required|string|max:50',
                'email' => 'required|email|unique:customers,email',
                'mobile' => 'required|string|max:50'
            ]);

            $customer = Customer::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'user_id' => $user_id
            ]);

            return redirect()->back()->with('success', 'Customer Updated successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    function CustomerList(Request $request)
    {
        $user_id = $request->header('id');

        return Customer::where('user_id', $user_id)->get();
    }
    function CustomerDelete(Request $request)
    {
        $user_id = $request->header('id');
        $customer_id = $request->id;

        $customer = Customer::where('id', $customer_id)->where('user_id', $user_id)->first();

        if ($customer) {
            $customer->delete();

            return Redirect::back()->with([
                'status' => true,
                'message' => 'Customer Deleted Successfully'
            ]);
        } else {
            return response()->json(['status' => 'failed', 'message' => "This user is not exist in the system"]);
        }
    }
    function CustomerByID(Request $request)
    {
        $user_id = $request->header('id');
        $customer_id = $request->input('id');

        return Customer::where('id', $customer_id)->where('user_id', $user_id)->first();
    }
    function CustomerUpdate(Request $request)
    {
        try {
            $user_id = $request->header('id');
            $customer_id = $request->input('id');

            $customer = Customer::where('id', $customer_id)->where('user_id', $user_id)->first();

            $data = $request->only(['name', 'email', 'mobile']);

            $customer->fill($data)->save();

            return redirect()->back()->with('message', 'Customer updated successfully');
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
