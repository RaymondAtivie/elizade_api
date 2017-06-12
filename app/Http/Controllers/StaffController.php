<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\SoapConnect;
use Illuminate\Validation\Rule;

class StaffController extends BaseController
{
    public function __construct(){
        parent::__construct();
        // TODO: Bring all SoapConnect objects to constructor
    }

    public function getAccounts(){
        $SC = new SoapConnect();
        $accounts = $SC->getAccounts();

        $data = [
            "success"=>true,
            "message"=>"accounts successfully retrieved",
            "data"=>$accounts
        ];

        return response()->json($data, 200);
    }

    public function findAccount($account_number){
        $SC = new SoapConnect();
        $account = $SC->findAccount($account_number);

        if($account){
            $data = [
                "success"=>true,
                "message"=>"account successfully retrieved",
                "data"=>$account
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"could not find account",
                "type"=>"not_found"
            ];
        }

        return response()->json($data, 200);
    }

    public function createAccount(Request $req){
         $post = $req->only(['accountName', 'email', 'phone', 'salesPerson', 'country', 'state', 'acctType', 'customerClass', 'customerCategory', 'origin', 'department', 'branch', 'businessSector', 'genBusinessGrp', 'customerPostGrp', 'vatPostGrp']);

         $customerClassOptions = ["High Fleet", "Medium Fleet", "Low Fleet"];
         $acctTypeOptions = ["Potential", "Actual"];
         $customerCategoryOptions = ["Individual", "Corporate"];
         $originOptions = ["Elizade", "Others"];
         $rules = [
            'accountName' => "required", 
            'email' => "required|email",
            'phone' => "required",
            'salesPerson' => "required",
            'country' => "required",
            'state' => "required",
            'acctType' => ['required', Rule::in($acctTypeOptions)],
            'customerClass' => ['required', Rule::in($customerClassOptions)],
            'customerCategory' => ['required', Rule::in($customerCategoryOptions)],
            'origin' => ['required', Rule::in($originOptions)],
            'department' => "required",
            'branch' => "required",
            'businessSector' => "required",
            'genBusinessGrp' => "required",
            'customerPostGrp' => "required",
            'vatPostGrp' => "required"
        ];
        $messages = [
            "acctType.in"=>"the :attribute option must be either of these: ".implode(", ", $acctTypeOptions),
            "customerClass.in"=>"the :attribute option must be either of these: ".implode(", ", $customerClassOptions),
            "customerCategory.in"=>"the :attribute option must be either of these: ".implode(", ", $customerCategoryOptions),
            "origin.in"=>"the :attribute option must be either of these: ".implode(", ", $originOptions)
        ];
        $validation = \Validator::make($post, $rules, $messages);

        if($validation->fails()){
            $errorMessage = $validation->errors()->getMessages();
            return response()->json([
                    "success" => false, 
                    "message" => $errorMessage,
                    "type" => "validation_error"
                ], 200);
        }

        $staffUsername = $req->get('user')->username;

        foreach ($post as $key => $value) {
            $$key = $value;
        }

        $SC = new SoapConnect();
        $account = $SC->createAccount($accountName, $email, $phone, $salesPerson, $country, $state, $acctType, $customerClass, $customerCategory, $origin, $department, $branch, $businessSector, $genBusinessGrp, $customerPostGrp, $vatPostGrp, $staffUsername);

        if($account){
            $data = [
                "success"=>true,
                "message"=>"successfully created account",
                "data"=>$account
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"account was not created",
                "type"=>"error_creating"
            ];
        }

        return response()->json($data, 200);
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

    public function findLead($lead_fullname){
        $SC = new SoapConnect();
        $lead = $SC->findLead($lead_fullname);

        if($lead){
            $data = [
                "success"=>true,
                "message"=>"lead successfully retrieved",
                "data"=>$lead
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"could not find lead",
                "type"=>"not_found"
            ];
        }

        return response()->json($data, 200);
    }

    public function createLead(Request $req){
         $post = $req->only(['companyName', 'firstname', 'lastname', 'category', 'buinessSector', 'email', 'businessPhone', 'mobilePhone', 'department', 'dimension', 'branch', 'state', 'prodOrService', 'likelyPurchaseDate', 'salesPerson']);

         $categoryOptions = ["Individual", "Corporate"];
         $dimensionOptions = ['Sales & Marketing', 'Aftersales'];
         $rules = [
            'companyName' => "required", 
            'firstname' => "required",
            'lastname' => "required",
            'category' => ['required', Rule::in($categoryOptions)],
            'buinessSector' => "required",
            'email' => "required|email",
            'businessPhone' => "required",
            'mobilePhone' => "required",
            'department' => "required",
            'dimension' => ['required', Rule::in($dimensionOptions)], 
            'branch' => "required",
            'state' => "required",
            'prodOrService' => "required", 
            'likelyPurchaseDate' => "required|date", 
            'salesPerson' => "required"
        ];
        $messages = [
            "category.in"=>"the :attribute option must be either of these: ".implode(", ", $categoryOptions),
            "dimension.in"=>"the :attribute option must be either of these: ".implode(", ", $dimensionOptions)
        ];
        $validation = \Validator::make($post, $rules, $messages);

        if($validation->fails()){
            $errorMessage = $validation->errors()->getMessages();
            return response()->json([
                    "success" => false, 
                    "message" => $errorMessage,
                    "type" => "validation_error"
                ], 200);
        }

        $staffUsername = $req->get('user')->username;

        foreach ($post as $key => $value) {
            $$key = $value;
        }

        $SC = new SoapConnect();
        $lead = $SC->createLead($companyName, $firstname, $lastname, $category, $buinessSector, $email, $businessPhone, $mobilePhone, $department, $dimension, $branch, $state, $prodOrService, $likelyPurchaseDate, $salesPerson, $staffUsername);

        if($lead){
            $data = [
                "success"=>true,
                "message"=>"successfully created lead",
                "data"=>$lead
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"lead was not created",
                "type"=>"error_creating"
            ];
        }

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

    public function createCase(Request $req){
         $post = $req->only(['customerNumber', 'description', 'title']);

         $rules = [
            'title' => "required", 
            'description' => "required", 
            'customerNumber' => "required", 
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

        foreach ($post as $key => $value) {
            $$key = $value;
        }

        $staffUsername = $req->get('user')->username;

        $SC = new SoapConnect();
        $case = $SC->staffCreateCase($customerNumber, $description, $title, $staffUsername);

        if($case){
            $data = [
                "success"=>true,
                "message"=>"successfully created case",
                "data"=>$case
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"case was not created",
                "type"=>"error_creating"
            ];
        }

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

    public function createAppointment(Request $req){
         $post = $req->only(['customerNumber', 'subject', 'dimension', 'startTime', 'endTime', 'rating', 'salesPerson']);

         $dimensionOptions = ['Sales & Marketing', 'Aftersales'];
         $ratingOptions = ['Hot', 'Warm', 'Cold'];
         $rules = [
            'subject' => "required", 
            'customerNumber' => "required", 
            'dimension' =>  ['required', Rule::in($dimensionOptions)], 
            'startTime' => "required|date",
            'endTime' => "required|date",
            'rating' => ['required', Rule::in($ratingOptions)], 
            'salesPerson' => "required"
        ];
        $messages = [
            "dimension.in"=>"the :attribute option must be either of these: ".implode(", ", $dimensionOptions),
            "rating.in"=>"the :attribute option must be either of these: ".implode(", ", $ratingOptions)
        ];
        $validation = \Validator::make($post, $rules, $messages);

        if($validation->fails()){
            $errorMessage = $validation->errors()->getMessages();
            return response()->json([
                    "success" => false, 
                    "message" => $errorMessage,
                    "type" => "validation_error"
                ], 200);
        }

        foreach ($post as $key => $value) {
            $$key = $value;
        }

        $staffUsername = $req->get('user')->username;

        $SC = new SoapConnect();
        $appointment = $SC->createAppointment($customerNumber, $subject, $dimension, $startTime, $endTime, $staffUsername);

        if($appointment){
            $data = [
                "success"=>true,
                "message"=>"successfully created appointment",
                "data"=>$appointment
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"appointment was not created",
                "type"=>"error_creating"
            ];
        }

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

    public function findOpportunity($topic){
        $SC = new SoapConnect();
        $opportunity = $SC->findOpportunity($topic);

        if($opportunity){
            $data = [
                "success"=>true,
                "message"=>"opportunity successfully retrieved",
                "data"=>$opportunity
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"could not find opportunity",
                "type"=>"not_found"
            ];
        }

        return response()->json($data, 200);
    }

    public function createOpportunity(Request $req){
         $post = $req->only(['topic', 'customerNumber', 'dimension', 'estCloseDate', 'probability', 'rating', 'salesPerson', 'staffUsername']);

         $dimensionOptions = ['Sales & Marketing', 'Aftersales'];
         $ratingOptions = ['Hot', 'Warm', 'Cold'];
         $rules = [
            'topic' => "required", 
            'customerNumber' => "required", 
            'dimension' =>  ['required', Rule::in($dimensionOptions)], 
            'estCloseDate' => "required|date",
            'probability' => "required|numeric|between:1,100",
            'rating' => ['required', Rule::in($ratingOptions)], 
            'salesPerson' => "required"
        ];
        $messages = [
            "dimension.in"=>"the :attribute option must be either of these: ".implode(", ", $dimensionOptions),
            "rating.in"=>"the :attribute option must be either of these: ".implode(", ", $ratingOptions)
        ];
        $validation = \Validator::make($post, $rules, $messages);

        if($validation->fails()){
            $errorMessage = $validation->errors()->getMessages();
            return response()->json([
                    "success" => false, 
                    "message" => $errorMessage,
                    "type" => "validation_error"
                ], 200);
        }

        foreach ($post as $key => $value) {
            $$key = $value;
        }

        $staffUsername = $req->get('user')->username;

        $SC = new SoapConnect();
        $contact = $SC->createOpportunity($topic, $customerNumber, $dimension, $estCloseDate, $probability, $rating, $salesPerson, $staffUsername);

        if($contact){
            $data = [
                "success"=>true,
                "message"=>"successfully created contact",
                "data"=>$contact
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"contact was not created",
                "type"=>"error_creating"
            ];
        }

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

    public function findContact($contact_name){
        $SC = new SoapConnect();
        $contact = $SC->findContact($contact_name);

        if($contact){
            $data = [
                "success"=>true,
                "message"=>"contact successfully retrieved",
                "data"=>$contact
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"could not find contact",
                "type"=>"not_found"
            ];
        }

        return response()->json($data, 200);
    }

    public function createContact(Request $req){
         $post = $req->only(['firstname', 'lastname', 'email', 'phone']);

         $rules = [
            'firstname' => "required", 
            'lastname' => "required", 
            'email' => "required|email", 
            'phone' => "required"
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

        foreach ($post as $key => $value) {
            $$key = $value;
        }

        $staffUsername = $req->get('user')->username;

        $SC = new SoapConnect();
        $contact = $SC->createContact($firstname, $lastname, $email, $phone, $staffUsername);

        if($contact){
            $data = [
                "success"=>true,
                "message"=>"successfully created contact",
                "data"=>$contact
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"contact was not created",
                "type"=>"error_creating"
            ];
        }

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

    public function findQuote($quote_number){
        $SC = new SoapConnect();
        $quote = $SC->findQuote($quote_number);

        if($quote){
            $data = [
                "success"=>true,
                "message"=>"quote successfully retrieved",
                "data"=>$quote
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"could not find quote",
                "type"=>"not_found"
            ];
        }

        return response()->json($data, 200);
    }

    public function createQuote(Request $req){
         $post = $req->only(['quoteName', 'customerNumber', 'dimension', 'priceListName', 'department', 'branch', 'salesPerson']);

         $dimensionOptions = ['Sales & Marketing', 'Aftersales'];
         $rules = [
            'quoteName' => "required", 
            'customerNumber' => "required", 
            'dimension' =>  ['required', Rule::in($dimensionOptions)], 
            'priceListName' => "required", 
            'department' => "required", 
            'branch' => "required",
            'salesPerson' => "required",
        ];
        $messages = [
            "dimension.in"=>"the :attribute option must be either of these: ".implode(", ", $dimensionOptions)
        ];
        $validation = \Validator::make($post, $rules, $messages);

        if($validation->fails()){
            $errorMessage = $validation->errors()->getMessages();
            return response()->json([
                    "success" => false, 
                    "message" => $errorMessage,
                    "type" => "validation_error"
                ], 200);
        }

        foreach ($post as $key => $value) {
            $$key = $value;
        }

        $SC = new SoapConnect();
        $quote = $SC->createQuote($quoteName, $customerNumber, $dimension, $priceListName, $department, $branch, $salesPerson);

        if($quote){
            $data = [
                "success"=>true,
                "message"=>"successfully created quote",
                "data"=>$quote
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"quote was not created",
                "type"=>"error_creating"
            ];
        }

        return response()->json($data, 200);
    }

    public function getOrders(){
        $SC = new SoapConnect();
        $orders = $SC->getOrders();

        $data = [
            "success"=>true,
            "message"=>"orders successfully retrieved",
            "data"=>$orders
        ];

        return response()->json($data, 200);
    }

    public function findOrder($order_number){
        $SC = new SoapConnect();
        $order = $SC->findOrder($order_number);

        if($order){
            $data = [
                "success"=>true,
                "message"=>"order successfully retrieved",
                "data"=>$order
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"could not find order",
                "type"=>"not_found"
            ];
        }

        return response()->json($data, 200);
    }

    public function createOrder(Request $req){
         $post = $req->only(['orderName', 'customerNumber', 'dimension', 'priceListName', 'department', 'branch', 'priceIncludeVat']);

         $priceIncludeVatOptions = ["Yes", "No"];
         $dimensionOptions = ['Sales & Marketing', 'Aftersales'];
         $rules = [
            'orderName' => "required", 
            'customerNumber' => "required", 
            'dimension' =>  ['required', Rule::in($dimensionOptions)], 
            'priceListName' => "required", 
            'department' => "required", 
            'branch' => "required", 
            'priceIncludeVat' => ['required', Rule::in($priceIncludeVatOptions)]
        ];
        $messages = [
            "priceIncludeVat.in"=>"the :attribute option must be either of these: ".implode(", ", $priceIncludeVatOptions),
            "dimension.in"=>"the :attribute option must be either of these: ".implode(", ", $dimensionOptions)
        ];
        $validation = \Validator::make($post, $rules, $messages);

        if($validation->fails()){
            $errorMessage = $validation->errors()->getMessages();
            return response()->json([
                    "success" => false, 
                    "message" => $errorMessage,
                    "type" => "validation_error"
                ], 200);
        }

        $staffUsername = $req->get('user')->username;

        foreach ($post as $key => $value) {
            $$key = $value;
        }

        $SC = new SoapConnect();
        $order = $SC->createOrder($orderName, $customerNumber, $dimension, $priceListName, $department, $branch, $priceIncludeVat, $staffUsername);

        if($order){
            $data = [
                "success"=>true,
                "message"=>"successfully created order",
                "data"=>$order
            ];
        }else{
            $data = [
                "success"=>false,
                "message"=>"order was not created",
                "type"=>"error_creating"
            ];
        }

        return response()->json($data, 200);
    }
}
