<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\SoapConnect;
use Illuminate\Validation\Rule;

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

    public function serviceDiagnisis(Request $req)
    {
        $post = $req->only(['vehicle_reg_no', 'vehicle_type', 'vehicle_model', 'vehicle_year', 'mileage', 'last_service_date', "issue", "datetime"]);

        $rules = [
            "vehicle_reg_no" => "required",
            "vehicle_type" => "required",
            "vehicle_model" => "required",
            "vehicle_year" => "required",
            "mileage" => "required",
            "last_service_date" => "required",
            "issue" => "required",
            "datetime" => "required",
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
            "Vehicle RegNo: {$post['vehicle_reg_no']}, \n Vehicle Type: {$post['vehicle_type']}, \n Vehicle Model: {$post['vehicle_model']}, \n Vehicle Year: {$post['vehicle_year']},  \n Persived Issue: {$post['issue']}",
            "Aftersales",
            $post['datetime'],
            $post['datetime'],
            "Service/Repairs",
            "dj6556",
            "Diagnosis",
            intval($post['mileage']),
            $post['last_service_date'],
            null
        );

        $data = [
            "success"=>true,
            "message"=>"Successfully reported for diagnosis",
            "data"=>$response
        ];

        return response()->json($data, 200);
    }

    public function serviceBodywork(Request $req)
    {
        $post = $req->only(['vehicle_reg_no', 'vehicle_type', 'vehicle_model', 'vehicle_year', "body_work_type", "datetime", "car_part"]);

        $bodyWorkOptions = ['full body work', 'partial body work'];
        $rules = [
            "vehicle_reg_no" => "required",
            "vehicle_type" => "required",
            "vehicle_model" => "required",
            "vehicle_year" => "required",
            "body_work_type" => ["required", Rule::in($bodyWorkOptions)],
            "datetime" => "required",
        ];
        if ($post['body_work_type'] !== 'full body work') {
            $rules['car_part'] = "required";
        }
        $messages = [
            "body_work_type.in"=>"the :attribute option must be either of these: ".implode(", ", $bodyWorkOptions),
        ];
        $validation = \Validator::make($post, $rules, $messages);

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

        $part = "";
        if ($post['body_work_type'] !== 'full body work') {
            $part = "\n Part that needs work: ".$post['car_part'];
        }

        $response = $SC->createCustomerAppointment(
            $user->customer_number,
            "Vehicle RegNo: {$post['vehicle_reg_no']}, \n Vehicle Type: {$post['vehicle_type']}, \n Vehicle Model: {$post['vehicle_model']}, \n Vehicle Year: {$post['vehicle_year']},  \n Body Work Type: {$post['body_work_type']} ".$part,
            "Aftersales",
            $post['datetime'],
            $post['datetime'],
            "Service/Repairs",
            "dj6556",
            "Body Work",
            intval(0),
            null,
            null
        );

        $data = [
            "success"=>true,
            "message"=>"Successfully reported for body work repair",
            "data"=>$response
        ];

        return response()->json($data, 200);
    }

    public function serviceRepair(Request $req)
    {
        $post = $req->only(['vehicle_reg_no', 'vehicle_type', 'vehicle_model', 'vehicle_year', "last_service_date", "datetime", "repair_type", "repair_description"]);

        $rules = [
            "vehicle_reg_no" => "required",
            "vehicle_type" => "required",
            "vehicle_model" => "required",
            "vehicle_year" => "required",
            "last_service_date" => "required",
            "datetime" => "required",
            "repair_type" => "required",
            "repair_description" => "required",
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
            "Vehicle RegNo: {$post['vehicle_reg_no']}, \n Vehicle Type: {$post['vehicle_type']}, \n Vehicle Model: {$post['vehicle_model']}, \n Vehicle Year: {$post['vehicle_year']}, \n Repair Type: {$post['repair_type']}, \n Issue Description: {$post['repair_description']}",
            "Aftersales",
            $post['datetime'],
            $post['datetime'],
            "Service/Repairs",
            "dj6556",
            "Mechanical Repair",
            intval(0),
            $post['last_service_date'],
            null
        );

        $data = [
            "success"=>true,
            "message"=>"Successfully reported for repair",
            "data"=>$response
        ];

        return response()->json($data, 200);
    }
}
