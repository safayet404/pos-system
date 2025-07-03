<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function Register(Request $request)
    {
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

        return response()->json([
            'status' => 'success',
            'message' => 'Employee registered successfully'
        ]);
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

    public function Logout(Request $request)
    {

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ])->cookie('token', '', -1);
    }
}
