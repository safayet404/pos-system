<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class UserController extends Controller
{





    function LoginPage()
    {
        return Inertia::render('LoginPage');
    }
    function RegistrationPage()
    {
        return Inertia::render('RegistrationPage');
    }

    function ResetPasswordPage()
    {
        return Inertia::render('ResetPasswordPage');
    }

    function  SendOtpPage()
    {
        return Inertia::render('SendOtpPage');
    }
    function VerifyOtpPage()
    {
        return Inertia::render('VerifyOtpPage');
    }
    function ProfilePage()
    {
        return Inertia::render('ProfilePage');
    }




    function UserRegistration(Request $request)
    {

        try {


            $validated = $request->validate([
                'name' => 'required|string|max:50',
                'email' => 'required|email|unique:users,email',
                'mobile' => 'required|string|max:50',
                'password' => 'required|string|min:6'


            ]);

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'password' => $validated['password']
            ]);

            return response()->json(['status' => 'success', 'message' => 'User Registration Successfull']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'message' => $e->getMessage()]);
        }
    }
    function UserLogin(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->select('id', 'password')->first();

        if ($user && Hash::check($password, $user->password)) {
            $token = JWTToken::CreateToken($email, $user->id);

            return response()->json([
                'status' => 'success',
                'message' => "User Login Successfull",
                'token' => $token
            ])->cookie('token', $token, time() + 60 * 24 * 30);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ]);
        }
    }
    function UserLogout(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ])->cookie('token', '', -1);
    }
    function SendOTPCode(Request $request)
    {
        $email = $request->input('email');
        $otp = rand(1000, 9999);

        $count = User::where('email', '=', $email)->count();

        if ($count == 1) {
            try {
                Mail::to($email)->send(new OTPMail($otp));
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Mail sending failed: ' . $e->getMessage()
                ], 500);
            }

            User::where('email', '=', $email)->update(['otp' => $otp]);

            return response()->json([
                'status' => 'success',
                'message' =>  "4 Digit {$otp} Code has been sent to your email"
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ]);
        }
    }

    function VerifyOTP(Request $request)
    {

        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)->where('otp', '=', $otp)->count();

        if ($count == 1) {
            User::where('email', '=', $email)->update(['otp' => '0']);

            $token = JWTToken::CreateTokenForSetPassword($email);
            return response()->json([
                'status' => 'success',
                'message' => 'OTP Verification Successfull',
                'token' => $token
            ])->cookie('token', $token, 60 * 24 * 30);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized',

            ]);
        }
    }
    function ResetPassword(Request $request)
    {

        try {

            $email = $request->input('email');
            $password = $request->input('password');
            $hashedPassword = Hash::make($password);

            User::where('email', '=', $email)->update(['password' => $hashedPassword]);

            return response()->json([
                'status' => 'success',
                'message' => 'Request Successfull'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ]);
        }
    }
    function UserProfile(Request $request)
    {
        $email = $request->header('email');
        $user = User::where('email', '=', $email)->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Request Success',
            'data' => $user
        ]);
    }



    function UpdateProfile(Request $request)
    {
        try {
            $email = $request->header('email');

            // Find the user by email
            $user = User::where('email', $email)->firstOrFail();

            // Get only valid fields from request
            $data = $request->only(['name', 'mobile', 'password']);

            // If password is present, hash it
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                // If password not provided, remove from update data
                unset($data['password']);
            }

            // Fill and save only provided fields
            $user->fill($data)->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong in update profile'
            ], 500);
        }
    }
}
