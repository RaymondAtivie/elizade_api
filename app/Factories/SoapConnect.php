<?php 

	namespace App\Factories;

    use Artisaninweb\SoapWrapper\SoapWrapper;
    use SoapClient;

	class SoapConnect
	{
		private $serviceUrl;
        private $soapWrapper;
        private $soapClient;

        public function __construct(){
            $soapWrapper = new SoapWrapper();
            $this->serviceUrl = "http://crm.elizade.net:5050/Service1.svc?wsdl";
            $this->soapWrapper = $soapWrapper;
            $this->soapClient = new SoapClient($this->serviceUrl);
        }

        private function makeNewCall($method, $data){
            $this->soapWrapper->add('CRW', function ($service) {
                $service
                    ->wsdl($this->serviceUrl)
                    ->trace(true);
            });

            $response = $this->soapWrapper->call('CRW.'.$method, $data);

            return $response;
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

        public function createCase($customerNumber, $title, $description){
            $method = "CreateCase";

            $data = [
                'accountnumber' => $customerNumber,
                'description'   => $description,
                'title'   => $title
            ];
            
            return $this->makeCall($method, $data)[0];
        }

        public function customerExist($customerNumber){
            $method = "CustomerExist";

            $data = [
                "customernumber" => $customerNumber
            ];
            
            $r = $this->makeCall($method, $data);

            if($r){
                return true;
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
                'custo' => $customerNumber,
                'product'   => $productName,
                'quantity'   => intval($quantity)
            ];
            
            return $this->makeCall($method, $data);
        }

	}