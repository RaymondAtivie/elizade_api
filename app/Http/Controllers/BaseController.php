<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\JWTAuth;

class BaseController extends Controller
{
    // logged in user
    public $user;
    
    // jwt
    public $auth;
    
    /*
    * Controller construct method
    */
    public function __construct()
    {
        $this->auth = new JWTAuth;
    }

    public function getAuthenticatedUser()
    {
        $user = $this->auth->parseToken()->authenticate()->getUser();
        $this->user = $user;
    }
}
