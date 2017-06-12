<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\SoapConnect;

class StaffController extends BaseController
{
    public function __construct(){
        parent::__construct();
    }

    public function getLeads(){
        $SC = new SoapConnect();
        $leads = $SC->getLeads();

        $data = [
            "success"=>true,
            "message"=>"leads successfully retrieved",
            "data"=>$leads
        ];

        return response()->json($data, 200);
    }

    public function getCases(){
        $SC = new SoapConnect();
        $cases = $SC->getCases();

        $data = [
            "success"=>true,
            "message"=>"cases successfully retrieved",
            "data"=>$cases
        ];

        return response()->json($data, 200);
    }

    public function getAppointments(){
        $SC = new SoapConnect();
        $appointments = $SC->getAppointments();

        $data = [
            "success"=>true,
            "message"=>"appointments successfully retrieved",
            "data"=>$appointments
        ];

        return response()->json($data, 200);
    }

    public function getOpportunities(){
        $SC = new SoapConnect();
        $opportunities = $SC->getOpportunities();

        $data = [
            "success"=>true,
            "message"=>"opportunities successfully retrieved",
            "data"=>$opportunities
        ];

        return response()->json($data, 200);
    }

    public function getContacts(){
        $SC = new SoapConnect();
        $contacts = $SC->getContacts();

        $data = [
            "success"=>true,
            "message"=>"contacts successfully retrieved",
            "data"=>$contacts
        ];

        return response()->json($data, 200);
    }

    public function getQuotes(){
        $SC = new SoapConnect();
        $quotes = $SC->getQuotes();

        $data = [
            "success"=>true,
            "message"=>"quotes successfully retrieved",
            "data"=>$quotes
        ];

        return response()->json($data, 200);
    }

    public function getOrders(Request $req){
        $SC = new SoapConnect();
        $orders = $SC->getOrders();

        $data = [
            "success"=>true,
            "message"=>"orders successfully retrieved",
            "data"=>$orders
        ];

        return response()->json($data, 200);
    }
}
