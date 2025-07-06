<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UnifiedLogin extends Controller
{
    function UnifiedLogin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $email = $validated['email'];

        $password = $validated['password'];

        $user = User::where('email', $email)->select('id', 'password')->first();



        if ($user && Hash::check($password, $user->password)) {


            $token = JWTToken::CreateToken($email, $user->id);

            return Redirect::to('/dashboard')->withCookie(cookie('token', $token, 60 * 24 * 30));
        }


        $employee = Employee::where('email', $request->email)->first();

        if ($employee && Hash::check($request->password, $employee->password)) {

            $token = JWTToken::CreateEmployeeToken($employee->email, $employee->id, $employee->user_id);


            return Redirect::to('/dashboard')->withCookie(cookie('token', $token, 60 * 24 * 30));
        }



        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    function UnifiedLogout(Request $request)
    {
        return Redirect::to('/login-page')->withCookie(cookie('token', '', -1));
    }
}
