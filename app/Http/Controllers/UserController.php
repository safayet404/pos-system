<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{

    function UserRegistration(Request $request)
    {

        try {
            $email = $request->input('email');
            $name = $request->input('name');
            $mobile = $request->input('mobile');
            $password = $request->input('password');

            User::create([
                'name' => $name,
                'email' => $email,
                'mobile' => $mobile,
                'password' => $password
            ]);

            return response()->json(['status' => 'success', 'message' => 'User Registration Successfull']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'message' => $e->getMessage()]);
        }
    }
    function UserLogin(Request $request) {}
    function UserLogout(Request $request) {}
    function SendOTPCode(Request $request) {}
    function VerifyOTP(Request $request) {}
    function ResetPassword(Request $request) {}
    function UserProfile(Request $request) {}
}
