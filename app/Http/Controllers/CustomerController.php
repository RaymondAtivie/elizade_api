<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\SoapConnect;

class CustomerController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createShowRoomAppointment(Request $req)
    {
        $post = $req->only(['location', 'datetime']);

        $rules = [
            "location" => "required",
            "datetime" => "required"
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

        $user = $this->auth->parseToken()->authenticate()->getUser();
        
        $SC = new SoapConnect();
        $response = $SC->createCustomerAppointment(
            $user->customer_number,
            "Prefered Branch: {$post['location']}",
            "Aftersales",
            $post['datetime'],
            $post['datetime'],
            "Visit Showroom",
            "dj6556",
            null,
            0,
            null,
            null
        );

        // dd($response);

        // $case = $SC->findCase($ticket->Ticketid);

        $data = [
            "success"=>true,
            "message"=>"Successfully scheduled showroom visit",
            "data"=>$response
        ];

        return response()->json($data, 200);
    }

    public function demonstrationAppointment(Request $req)
    {
        $post = $req->only(['location', 'datetime']);

        $rules = [
            "location" => "required",
            "datetime" => "required"
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

        $user = $this->auth->parseToken()->authenticate()->getUser();
        
        $SC = new SoapConnect();
        $response = $SC->createCustomerAppointment(
            $user->customer_number,
            "Prefered Branch: {$post['location']}",
            "Aftersales",
            $post['datetime'],
            $post['datetime'],
            "Schedule a demonstration",
            "dj6556",
            null,
            0,
            null,
            null
        );

        $data = [
            "success"=>true,
            "message"=>"Successfully scheduled demonstration",
            "data"=>$response
        ];

        return response()->json($data, 200);
    }

    public function callAppointment(Request $req)
    {
        $post = $req->only(['phone', 'datetime', 'comment']);

        $rules = [
            "phone" => "required",
            "datetime" => "required",
            "comment" => "required"
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

        $user = $this->auth->parseToken()->authenticate()->getUser();
        
        $SC = new SoapConnect();
        $response = $SC->createCustomerAppointment(
            $user->customer_number,
            "Customer Phone Number: {$post['phone']}, \n Comment: {$post['comment']}",
            "Aftersales",
            $post['datetime'],
            $post['datetime'],
            "Speak to a sales executive",
            "dj6556",
            null,
            0,
            null,
            null
        );

        $data = [
            "success"=>true,
            "message"=>"Successfully scheduled Call",
            "data"=>$response
        ];

        return response()->json($data, 200);
    }
}
