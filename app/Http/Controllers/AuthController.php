<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\SoapConnect;
use App\User;

class AuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login(Request $request)
    {
        // get credentials
        $credentials = $request->only(["email", "password"]);

        // attempt user login
        $jwt = $this->auth->attempt($credentials);

        $u = $jwt->getUser();
        $u->token = $jwt->getToken();

        return response()->json([
            "success"=>true,
            "data"=>[
                // "token" => $jwt->getToken(),
                "user" => $u
            ]
        ], 200);
    }

    public function loginStaff(Request $request)
    {
        $credentials = $request->only(["username", "password"]);

        $rules = [
            "username" => "required",
            "password" => "required"
        ];
        // validation
        $validation = \Validator::make($credentials, $rules);
        
        if ($validation->fails()) {
            // validation failed
            $errorMessage = $validation->errors()->getMessages();
            // return a response
            return response()->json([
                    "success" => false,
                    "message" => $errorMessage,
                    "type" => "validation_error"
                ], 422);
        }

        // attempt user login
        $jwt = $this->auth->attemptStaff($credentials);

        $u = $jwt->getUser();
        $u['token'] = $jwt->getToken();
        return response()->json([
            "success"=>true,
            "data"=>[
                "user" => $u
            ]
        ], 200);
    }

    public function signup(Request $request)
    {
        // get request data
        $data = $request->only(["customer_number", "email", "password"]);
        // rules
        $rules = [
            "customer_number" => "required|unique:users",
            "email" => "required|email|unique:users",
            "password" => "required|min:6",
            // "role" => "nullable|sometimes|exists:roles,name"
        ];

        // validation
        $validation = \Validator::make($data, $rules);

        // if validation passes
        if ($validation->passes()) {
            $SC = new SoapConnect();

            $uo = $SC->customerExist($data['customer_number']);

            if (!$uo) {
                return response()->json([
                    "success" => false,
                    "message" => "This customer number doesn't exist on the system",
                    ], 200);
            }
            
            // create new user
            $user = new User;
            $user->customer_number = $data['customer_number'];
            $user->email = $data['email'];
            $user->name = $uo[0]->CustomerName;
            $user->password = bcrypt($data['password']);
            $user->activated = 1;
            
            $user->save();

            $jwt = $this->auth->attempt($data);
            // create user token

            $token = $jwt->getToken();
            $user  = $jwt->getUser();
            $user->token = $token;

            // return a response
            return response()->json([
                "success" => true,
                "message" => "Successfully created user",
                "data" => [
                    "user" => $user,
                    // "token" => $token
                ]], 201);
        }

        // validation failed
        $errorMessage = $validation->errors()->getMessages();
        // return a response
        return response()->json([
                "success" => false,
                "message" => $errorMessage,
                "type" => "validation_error"
            ], 422);
    }

    public function staffLogin(Request $request)
    {
        // get credentials
        $credentials = $request->only(["username", "password"]);
        
        $SC = new SoapConnect();
        $uo = $SC->staffLogin($data['username'], $data['password']);

        if (!$SC->staffLogin($data['username'], $data['password'])) {
            return response()->json([
                "success"=>false,
                "type"=>"login_failed",
                "message"=>"Invalid email or password.",
            ], 200);
        }

        $u = $jwt->getUser();
        $u->token = $jwt->getToken();
        return response()->json([
            "success"=>true,
            "data"=>[
                "user" => $u
            ]
        ], 200);
    }
}
