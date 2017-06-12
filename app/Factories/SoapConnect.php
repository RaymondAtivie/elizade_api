<?php 

	namespace App\Factories;

    use SoapClient;

	class SoapConnect
	{
		private $serviceUrl;
        private $soapClient;

        public function __construct(){
            $this->serviceUrl = "http://crm.elizade.net:5050/Service1.svc?wsdl";
            $this->soapClient = new SoapClient($this->serviceUrl, [
                    'trace' =>true,
                    'connection_timeout' => 500000,
                    'cache_wsdl' => WSDL_CACHE_NONE,
                    'keep_alive' => false
                ]);
        }

        public function showMethods(){
            return $this->soapClient->__getFunctions();
        }

        private function makeNewCall($method, $data){
            // $this->soapWrapper->add('CRW', function ($service) {
            //     $service
            //         ->wsdl($this->serviceUrl)
            //         ->trace(true);
            // });

            // $response = $this->soapWrapper->call('CRW.'.$method, $data);

            // return $response;
        }

        private function makeCall($method, $data){
            try {

                $response = $this->soapClient->$method($data);
                return json_decode($response->{$method."Result"});

            } catch (Exception $e) {
                echo "<h1>Cannot connnect to Loan Application Server Right now. Please try again Later<h1>";
                die();
            }
        }

        //GET GENERAL DATA FUNCTIONS

        public function getCountries(){
            $method = "GetCountries";

            $countries = $this->makeCall($method, []);

            if($countries){
                $_countries = [];
                foreach ($countries as $c) {
                    $_countries[] = $c->Country;
                }
                return $_countries;
            }else{
                return false;
            }
        }

        public function getStates(){
            $method = "GetStates";

            $states = $this->makeCall($method, []);

            if($states){
                $_states = [];
                foreach ($states as $c) {
                    $_states[] = $c->State;
                }
                return $_states;
            }else{
                return false;
            }
        }

        public function getDepartments(){
            $method = "GetDepartments";

            $departments = $this->makeCall($method, []);

            if($departments){
                $_departments = [];
                foreach ($departments as $c) {
                    $_departments[] = $c->Department;
                }
                return $_departments;
            }else{
                return false;
            }
        }

        public function getBranches(){
            $method = "GetBranches";

            $branches = $this->makeCall($method, []);

            if($branches){
                $_branches = [];
                foreach ($branches as $c) {
                    $_branches[] = $c->Branch;
                }
                return $_branches;
            }else{
                return false;
            }
        }

        public function getBusinessSectors(){
            $method = "GetBusinessSectors";

            $bss = $this->makeCall($method, []);

            if($bss){
                $_bss = [];
                foreach ($bss as $c) {
                    $_bss[] = $c->BusinessSector;
                }
                return $_bss;
            }else{
                return false;
            }
        }

        public function getGenBizPostingGrp(){
            $method = "GetGenBizPostingGrp";

            $bss = $this->makeCall($method, []);

            if($bss){
                $_bss = [];
                foreach ($bss as $c) {
                    $_bss[] = $c->GenBusPostingGrp;
                }
                return $_bss;
            }else{
                return false;
            }
        }

        public function getCustPostingGr(){
            $method = "GetCustPostingGrp";

            $bss = $this->makeCall($method, []);

            if($bss){
                $_bss = [];
                foreach ($bss as $c) {
                    $_bss[] = $c->CustomerPostingGrp;
                }
                return $_bss;
            }else{
                return false;
            }
        }

        public function getVatPostingGrp(){
            $method = "GetVatPostingGrp";

            $bss = $this->makeCall($method, []);

            if($bss){
                $_bss = [];
                foreach ($bss as $c) {
                    $_bss[] = $c->VatPostingGrp;
                }
                return $_bss;
            }else{
                return false;
            }
        }

        public function getPricelists(){
            $method = "GetPricelists";

            $bss = $this->makeCall($method, []);

            if($bss){
                $_bss = [];
                foreach ($bss as $c) {
                    $_bss[] = $c->Name;
                }
                return $_bss;
            }else{
                return false;
            }
        }

        public function getSalespersons(){
            $method = "GetSalespersons";

            $salesperson = $this->makeCall($method, []);

            if($salesperson){
                $_salesperson = [];
                foreach ($salesperson as $c) {
                    $_salesperson[] = $c->Salesperson;
                }
                return $_salesperson;
            }else{
                return false;
            }
        }


        //CUSTOMERS FUNCTIONS

        public function createCase($customerNumber, $title, $description){
            $method = "CreateCase";

            $data = [
                'accountnumber' => $customerNumber,
                'description'   => $description,
                'title'   => $title
            ];
            
            $case = $this->makeCall($method, $data);

            if($case){
                return $case[0];
            }else{
                return false;
            }
        }

        public function customerExist($customerNumber){
            $method = "CustomerExist";

            $data = [
                "customernumber" => $customerNumber
            ];
            
            $r = $this->makeCall($method, $data);

            if($r){
                return $r;
            }else{
                return false;
            }
        }

        public function findCase($ticketId){
            $method = "FindCase";

            $data = [
                "ticketid" => $ticketId
            ];

            $case = $this->makeCall($method, $data);

            if($case){
                return $case[0];
            }else{
                return false;
            }
        }

        public function getCases(){
            $method = "GetCases";
            
            return $this->makeCall($method, []);
        }

        public function getAppointments(){
            $method = "GetAppointments";
            
            return $this->makeCall($method, []);
        }

        public function getProducts(){
            $method = "GetProducts";
            
            return $this->makeCall($method, []);
        }

        public function makeQuoteRequest($customerNumber, $productName, $quantity){
            $method = "QuoteRequest";

            $data = [
                'custno' => $customerNumber,
                'product'   => $productName,
                'quantity'   => intval($quantity)
            ];

            $r = $this->makeCall($method, $data);

            if($r){
                return true;
            }else{
                return false;
            }
        }


        ///STAFF FUNCTIONS

        public function staffLogin($username, $password){
            $method = "Login";

            $data = [
                'username' => $username,
                'password'   => $password
            ];

            $r = $this->makeCall($method, $data);

            if($r){
                return true;
            }else{
                return false;
            }
        }

        public function staffDetails($username){
            $method = "GetUserDetails";

            $data = [
                'username' => $username
            ];

            $r = $this->makeCall($method, $data);

            if($r){
                return $r[0];
            }else{
                return false;
            }
        }

        public function getStaffDetails($username, $password){
            if(!$this->staffLogin($username, $password)){
                return false;
            }
            $name = $this->staffDetails($username);

            if($name){
                return [
                    "name"=>$name->StaffName,
                    "username"=>$username,
                ];
            }else{
                return false;
            }
        }

        //NOTE: not confirmed // Too Long to retrieve
        public function getAccounts(){
            // ini_set('default_socket_timeout', 600);
            $method = "GetAccounts";
            
            return $this->makeCall($method, []);
        }

        //NOTE: Too long to retrieve
        public function getLeads(){
            $method = "GetLeads";
            
            return $this->makeCall($method, []);
        }

        //NOTE: Method does not exist
        public function getOpportunities(){
            $method = "GetOpportunities";
            
            return $this->makeCall($method, []);
        }

        public function getContacts(){
            $method = "GetContacts";
            
            return $this->makeCall($method, []);
        }

        //NOTE: Quotes don't return usefull info
        public function getQuotes(){
            $method = "GetQuotes";
            
            return $this->makeCall($method, []);
        }

        public function getOrders(){
            $method = "GetOrders";
            
            return $this->makeCall($method, []);
        }

        public function getProduct(){
            $method = "GetProduct";
            
            return $this->makeCall($method, []);
        }

	}