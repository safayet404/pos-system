<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\Employee;
use App\Models\User;
use Exception;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
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

    function ProfilePage(Request $request)
    {
        $email = $request->header('email');
        $user = Employee::where('email', $email)->first() ??   User::where('email', $email)->first();
        return Inertia::render('ProfilePage', [
            'user' => $user
        ]);
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

            return redirect('/login-page')->with('success', 'User registered!');
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
    public function SendOTPCode(Request $request)
    {
        $email = $request->input('email');
        $otp = rand(1000, 9999);

        $count = User::where('email', $email)->count();

        if ($count == 1) {
            try {
                Mail::to($email)->send(new OTPMail($otp));
            } catch (\Exception $e) {
                return Redirect::back()->withErrors([
                    'email' => 'Mail sending failed: ' . $e->getMessage()
                ]);
            }

            User::where('email', $email)->update(['otp' => $otp]);

            $data = ['message' => "Otp send successfully", 'status' => true, 'error' => ''];
            $request->session()->put('email', $email);
            return redirect('/verify-otp-page')->with('flash', $data);
        } else {
            return Redirect::back()->withErrors([
                'email' => 'Unauthorized email address'
            ]);
        }
    }

    function VerifyOTP(Request $request)
    {
        $email = $request->session()->get('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)
            ->where('otp', '=', $otp)
            ->count();

        if ($count === 1) {
            User::where('email', '=', $email)->update(['otp' => '0']);
            $request->session()->put('otp_verify', 'yes');
            $token = JWTToken::CreateTokenForSetPassword($email);

            return redirect('/reset-password-page')
                ->with('message', 'OTP verification successful')
                ->cookie('token', $token, 60 * 24 * 30);
        } else {
            return redirect()->back()
                ->with('error', 'Invalid OTP, please try again');
        }
    }

    function ResetPassword(Request $request)
    {
        try {
            $email = $request->session()->get('email');
            $password = $request->input('password');
            $hashedPassword = Hash::make($password);

            $otp_verify = $request->session()->get('otp_verify', 'default');

            if ($otp_verify == "yes") {

                User::where('email', '=', $email)->update(['password' => $hashedPassword]);
                $request->session()->flush();
                return redirect('/login-page')->with('message', 'Password updated successfully');
            }
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
            $user = User::where('email', $email)->firstOrFail();

           
            $request->validate([
                'name' => 'required|string|max:255',
                'mobile' => 'nullable|string|max:20',
                'password' => 'nullable|string|min:6',
            ]);

            $data = $request->only(['name', 'mobile', 'password']);

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->fill($data)->save();

            return redirect()->back()->with('message', 'Profile updated successfully');
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong in update profile'
            ], 500);
        }
    }
}
