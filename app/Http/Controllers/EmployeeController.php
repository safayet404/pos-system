<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class EmployeeController extends Controller
{

    function EmployeePage(Request $request)
    {
        $user_id = request()->header('id');
        $list = Employee::where("user_id",$user_id)->get();

        return Inertia::render('EmployeePage',['list' => $list]);
    }
    function EmployeeSavePage(Request $request)
    {
        $user_id = $request->header('id');

        $employee_id = $request->query('id');
        if ($employee_id != 0) {
            $list = Employee::where('id', $employee_id)->where('user_id', $user_id)->first();
            return Inertia::render('EmployeeSavePage', ['list' => $list]);
        } else {

            return Inertia::render('EmployeeSavePage');
        }
    }


    public function Register(Request $request)
    {
        try {
            $userId = $request->header('id');
            $request->validate([
                'name' => 'required|string|max:50',
                'email' => 'required|email|unique:employees,email',
                'mobile' => 'required|string|max:20',
                'password' => 'required|string|min:6'
            ]);
    
            Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'user_id' => $userId,
            ]);
    
           return redirect()->back()->with("message","employee created successfully");
        } catch (Exception $e) {
            return redirect()->back()->withErrors("message","Something went wrong");

        }
   
    }

    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $employee = Employee::where('email', $request->email)->first();

        if ($employee && Hash::check($request->password, $employee->password)) {
            $token = JWTToken::CreateEmployeeToken($employee->email, $employee->id, $employee->user_id);

            return response()->json([
                'status' => 'success',
                'message' => 'Employee login successful',
                'token' => $token
            ])->cookie('token', $token, 60 * 24 * 30);
        }

        return response()->json([
            'status' => 'failed',
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function Profile(Request $request)
    {
        $employeeId = $request->header('employee-id');

        $employee = Employee::find($employeeId);

        return response()->json([
            'status' => 'success',
            'data' => $employee
        ]);
    }

    function UpdateEmloyee(Request $request)
    {
        try {
            $user_id = $request->header('id');
            $employee_id = $request->input('id');

            $employee = Employee::where('id', $employee_id)->where('user_id', $user_id)->first();
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:20',
                'mobile' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:6',
            ]);

            $data = $request->only(['name', 'mobile','email', 'password']);

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $employee->fill($data)->save();

            return redirect()->back()->with('message', 'Employee updated successfully');
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong in update employee'
            ], 500);
        }
    }

    public function Logout(Request $request)
    {

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ])->cookie('token', '', -1);
    }
}
