<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\SoapConnect;

class CaseController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createCase(Request $req)
    {
        $post = $req->only(['title', 'description']);

        $rules = [
            "title" => "required",
            "description" => "required"
        ];
        $validation = \Validator::make($post, $rules);

        if ($validation->fails()) {
            // validation failed
            $errorMessage = $validation->errors()->getMessages();
            // return a response
            return response()->json([
                    "success" => false,
                    "message" => $errorMessage,
                    "type" => "validation_error"
                ], 200);
        }

        $user = $req->get("user");
        
        $SC = new SoapConnect();
        $ticket = $SC->createCase($user->customer_number, $post['title'], $post['description']);

        $case = $SC->findCase($ticket->Ticketid);

        $data = [
            "success"=>true,
            "message"=>"successfully created case",
            "data"=>$case
        ];

        return response()->json($data, 200);
    }

    public function getCases(Request $req)
    {
        $user = $req->get("user");
        
        $SC = new SoapConnect();
        $cases = collect($SC->getCases());

        $myCases = [];
        foreach ($cases as $case) {
            if ($case->CustomerId == $user->customer_number) {
                $myCases[] = $case;
            }
        }

        $data = [
            "success"=>true,
            "message"=>"users cases successfully retrieved",
            "data"=>$myCases
        ];

        return response()->json($data, 200);
    }

    public function getCase(Request $req, $ticket_id)
    {
        $SC = new SoapConnect();
        $case = $SC->findCase($ticket_id);

        if (!$case) {
            return response()->json([
                    "success" => false,
                    "message" => "this case does not exist",
                    "type" => "validation_error"
                ], 200);
        }

        $data = [
            "success"=>true,
            "message"=>"case successfully retrieved",
            "data"=>$case
        ];
        
        return response()->json($data, 200);
    }

    public function getAppointments(Request $req)
    {
        $user = $req->get("user");
        
        $SC = new SoapConnect();
        $appointments = collect($SC->getAppointments());

        $myAppointments = [];
        $appointmentNames = ['Visit Showroom', 'Schedule a demonstration', 'Speak to a sales executive'];
        foreach ($appointments as $a) {
            if ($a->CustomerID == $user->customer_number) {
                if (in_array($a->AppointmentType, $appointmentNames)) {
                    $myAppointments[] = $a;
                }
            }
        }

        $data = [
            "success"=>true,
            "message"=>"users appointments successfully retrieved",
            "data"=>$myAppointments
        ];

        return response()->json($data, 200);
    }

    public function getServices(Request $req)
    {
        $user = $req->get("user");
        
        $SC = new SoapConnect();
        $appointments = collect($SC->getAppointments());

        $myAppointments = [];
        $appointmentNames = ['Service/Repairs'];
        foreach ($appointments as $a) {
            if ($a->CustomerID == $user->customer_number) {
                if (in_array($a->AppointmentType, $appointmentNames)) {
                    $myAppointments[] = $a;
                }
            }
        }

        $data = [
            "success"=>true,
            "message"=>"users service/repairs successfully retrieved",
            "data"=>$myAppointments
        ];

        return response()->json($data, 200);
    }
}
