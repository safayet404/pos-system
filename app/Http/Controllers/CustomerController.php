<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
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

            return response()->json([
                'status' => 'success',
                'data' => $customer
            ]);
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
        $customer_id = $request->input('id');

        $customer = Customer::where('id', $customer_id)->where('user_id', $user_id)->first();

        if ($customer) {

            return Customer::where('id', $customer_id)->where('user_id', $user_id)->delete();
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

            return response()->json([
                'status' => 'success',
                'message' => 'Customer updated successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
