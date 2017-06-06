<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\SoapConnect;

class OrderController extends BaseController
{
    public function __construct(){
        parent::__construct();
    }

    public function makeQuoteRequest(Request $req){
        $post = $req->only(['product_name', 'quantity']);

         $rules = [
            "product_name" => "required",
            "quantity" => "required|numeric"
        ];
        $validation = \Validator::make($post, $rules);

        if($validation->fails()){
            $errorMessage = $validation->errors()->getMessages();
            
            return response()->json([
                    "success" => false, 
                    "message" => $errorMessage,
                    "type" => "validation_error"
                ], 200);
        }
        $user = $req->get("user");
        
        $SC = new SoapConnect();
        //TODO: Confirm product name first
        $r = $SC->makeQuoteRequest($user->customer_number, $post['product_name'], $post['quantity']);

        if($r){
            $data = [
                "success"=>true,
                "message"=>"quote request successful",
                "data"=>$r
            ];     
        }else{
            $data = [
                "success"=>false,
                "message"=>"quote request not successful",
                "data"=>$r
            ];
        }

        return response()->json($data, 200);

        


    }
}
