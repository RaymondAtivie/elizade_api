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

    public function findProduct($product_name){
        $SC = new SoapConnect();
        $product = $SC->findProduct($product_name);

        if($product){
            $data = [
                "success"=>true,
                "message"=>"product successfully retrieved",
                "data"=>$product
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"could not find product",
                "type"=>"not_found"
            ];
        }

        return response()->json($data, 200);
    }
}
