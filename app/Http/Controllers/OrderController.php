<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\SoapConnect;

class OrderController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function makeQuoteRequestVehicle(Request $req)
    {
        $post = $req->only(['product_name', 'quantity', 'billname', 'vehicle_model', 'vehicle_year', 'vehicle_reg_no']);

        $rules = [
            "product_name" => "required",
            "quantity" => "required|numeric",
            "billname" => "required",
            "vehicle_model" => "required",
            "vehicle_year" => "required",
            "vehicle_reg_no" => "required",
        ];
        $validation = \Validator::make($post, $rules);

        if ($validation->fails()) {
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
        $r = $SC->makeQuoteRequest(
            $user->customer_number,
            $post['product_name'],
            $post['quantity'],
            $post['billname'],
            $post['vehicle_model'],
            $post['vehicle_year'],
            $post['vehicle_reg_no'],
            null,
            null,
            null
        );

        if ($r) {
            $data = [
                "success"=>true,
                "message"=>"quote request successful",
                "data"=>$r
            ];
        } else {
            $data = [
                "success"=>false,
                "message"=>"quote request not successful",
                "data"=>$r
            ];
        }

        return response()->json($data, 200);
    }

    public function makeQuoteRequestParts(Request $req)
    {
        $post = $req->only(['product_name', 'quantity', 'billname', 'chasisno', 'part_desc', 'part_no']);

        $rules = [
            "product_name" => "required",
            "quantity" => "required|numeric",
            "billname" => "required",
            "chasisno" => "required",
            "part_desc" => "required",
            "part_no" => "required",
        ];
        $validation = \Validator::make($post, $rules);

        if ($validation->fails()) {
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
        $r = $SC->makeQuoteRequest(
            $user->customer_number,
            $post['product_name'],
            $post['quantity'],
            $post['billname'],
            null,
            null,
            null,
            $post['chasisno'],
            $post['part_desc'],
            $post['part_no']
        );

        if ($r) {
            $data = [
                "success"=>true,
                "message"=>"quote request successful",
                "data"=>$r
            ];
        } else {
            $data = [
                "success"=>false,
                "message"=>"quote request not successful",
                "data"=>$r
            ];
        }

        return response()->json($data, 200);
    }
}
