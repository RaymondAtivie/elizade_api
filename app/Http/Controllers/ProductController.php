<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\SoapConnect;

class ProductController extends BaseController
{
    public function __construct(){
        parent::__construct();
    }

    public function getProducts(){
        $SC = new SoapConnect();
        $r = $SC->getProducts();

        
        return response()->json($r, 200);
        
    }
}
