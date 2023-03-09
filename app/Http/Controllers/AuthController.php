<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ResponseTrait;
    
    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'                  => 'required|max:40|string',
            'email'                 => 'required|max:40|email|unique:users,email',
            'phone'                 => 'required|regex:/[6-9][0-9]{9}/|unique:users,phone',
            'dob'                   => 'required|date',
            'city'                  => 'required|max:40|string',
            'password'              => 'required|confirmed|min:8|max:40',
            'password_confirmation' => 'required'
        ]);

        if ($validation->fails())
            return $this->validationErrorsResponse($validation);

        User::create($request->only(['name', 'email', 'phone', 'city', 'dob']) + [
            'password'                  => Hash::make($request->password)
        ]);

        return $this->returnResponse(true, "User Created Successfully");
    }

    public function login(Request $request)
    {
        //Validation
        $validation   = Validator::make($request->all(), [
            'email'     => 'required|email|exists:users,email',
            'password'  => 'required',
        ]);

        if ($validation->fails())
            return $this->validationErrorsResponse($validation);

        $user = User::where('email', $request->email)->first();

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $apiToken = $user->createToken("API TOKEN")->plainTextToken;
            return $this->returnResponse(true, "Login Successfully", $apiToken);
        } else {
            return $this->returnResponse(false, "Password Incorrect");
        }
    }

    public function adminLogin(Request $request)
    {
        //Validation
        $validation   = Validator::make($request->all(), [
            'email'     => 'required|email|exists:users,email',
            'password'  => 'required',
        ]);

        if ($validation->fails())
            return $this->validationErrorsResponse($validation);

        $user = User::where('email', $request->email)->first();

        if ($user->type == 'admin') {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $apiToken = $user->createToken("API TOKEN")->plainTextToken;
                return $this->returnResponse(true, "Login Successfully", $apiToken);
            } else {
                return $this->returnResponse(false, "Password Incorrect");
            }
        }
        else{
            return $this->returnResponse(false, "Not allowed");
        }
    }
}
