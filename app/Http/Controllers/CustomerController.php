<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    function CustomerCreate(Request $request)
    {
        $user_id = $request->header('id');

        return Customer::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'user_id' => $user_id
        ]);
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

        return Customer::where('id', $customer_id)->where('user_id', $user_id)->delete();
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
