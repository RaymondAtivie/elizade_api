<?php 

    namespace App\Factories;

use SoapClient;

class SoapConnect
{
    private $serviceUrl;
    private $soapClient;

    public function __construct()
    {
        $this->serviceUrl = "http://crm.elizade.net:5050/Service1.svc?wsdl";
        $this->soapClient = new SoapClient($this->serviceUrl, [
                    'trace' =>true,
                    'connection_timeout' => 500000,
                    'cache_wsdl' => WSDL_CACHE_NONE,
                    'keep_alive' => false
                ]);
    }

    public function showMethods()
    {
        return $this->soapClient->__getFunctions();
    }

    private function makeNewCall($method, $data)
    {
        // $this->soapWrapper->add('CRW', function ($service) {
            //     $service
            //         ->wsdl($this->serviceUrl)
            //         ->trace(true);
            // });

            // $response = $this->soapWrapper->call('CRW.'.$method, $data);

            // return $response;
    }

    private function makeCall($method, $data)
    {
        try {
            $response = $this->soapClient->$method($data);
            // dd($data, $method, $response);
            return json_decode($response->{$method."Result"});
        } catch (Exception $e) {
            echo "<h1>Cannot connnect to Loan Application Server Right now. Please try again Later<h1>";
            die();
        }
    }

    // GET GENERAL DATA FUNCTIONS ////////////////

    public function getCountries()
    {
        $method = "GetCountries";

        $countries = $this->makeCall($method, []);

        if ($countries) {
            $_countries = [];
            foreach ($countries as $c) {
                $_countries[] = $c->Country;
            }
            return $_countries;
        } else {
            return false;
        }
    }

    public function getStates()
    {
        $method = "GetStates";

        $states = $this->makeCall($method, []);

        if ($states) {
            $_states = [];
            foreach ($states as $c) {
                $_states[] = $c->State;
            }
            return $_states;
        } else {
            return false;
        }
    }

    public function getDepartments()
    {
        $method = "GetDepartments";

        $departments = $this->makeCall($method, []);

        if ($departments) {
            $_departments = [];
            foreach ($departments as $c) {
                $_departments[] = $c->Department;
            }
            return $_departments;
        } else {
            return false;
        }
    }

    public function getBranches()
    {
        $method = "GetBranches";

        $branches = $this->makeCall($method, []);

        if ($branches) {
            $_branches = [];
            foreach ($branches as $c) {
                $_branches[] = $c->Branch;
            }
            return $_branches;
        } else {
            return false;
        }
    }

    public function getBusinessSectors()
    {
        $method = "GetBusinessSectors";

        $bss = $this->makeCall($method, []);

        if ($bss) {
            $_bss = [];
            foreach ($bss as $c) {
                $_bss[] = $c->BusinessSector;
            }
            return $_bss;
        } else {
            return false;
        }
    }

    public function getGenBizPostingGrp()
    {
        $method = "GetGenBizPostingGrp";

        $bss = $this->makeCall($method, []);

        if ($bss) {
            $_bss = [];
            foreach ($bss as $c) {
                $_bss[] = $c->GenBusPostingGrp;
            }
            return $_bss;
        } else {
            return false;
        }
    }

    public function getCustPostingGr()
    {
        $method = "GetCustPostingGrp";

        $bss = $this->makeCall($method, []);

        if ($bss) {
            $_bss = [];
            foreach ($bss as $c) {
                $_bss[] = $c->CustomerPostingGrp;
            }
            return $_bss;
        } else {
            return false;
        }
    }

    public function getVatPostingGrp()
    {
        $method = "GetVatPostingGrp";

        $bss = $this->makeCall($method, []);

        if ($bss) {
            $_bss = [];
            foreach ($bss as $c) {
                $_bss[] = $c->VatPostingGrp;
            }
            return $_bss;
        } else {
            return false;
        }
    }

    public function getPricelists()
    {
        $method = "GetPricelists";

        $bss = $this->makeCall($method, []);

        if ($bss) {
            $_bss = [];
            foreach ($bss as $c) {
                $_bss[] = $c->Name;
            }
            return $_bss;
        } else {
            return false;
        }
    }

    public function getSalespersons()
    {
        $method = "GetSalespersons";

        $salesperson = $this->makeCall($method, []);

        if ($salesperson) {
            $_salesperson = [];
            foreach ($salesperson as $c) {
                $_salesperson[] = $c->Salesperson;
            }
            return $_salesperson;
        } else {
            return false;
        }
    }


    public function getCars()
    {
        $method = "GetCars";

        $cars = $this->makeCall($method, []);

        if ($cars) {
            return $cars;
        } else {
            return false;
        }
    }


    // CUSTOMERS FUNCTIONS ////////////////

    public function createCase($customerNumber, $title, $description)
    {
        $method = "CreateCase";

        $data = [
                'accountnumber' => $customerNumber,
                'description'   => $description,
                'title'   => $title
            ];
            
        $case = $this->makeCall($method, $data);

        if ($case) {
            return $case[0];
        } else {
            return false;
        }
    }

    public function customerExist($customerNumber)
    {
        $method = "CustomerExist";

        $data = [
                "customernumber" => $customerNumber
            ];
            
        $r = $this->makeCall($method, $data);

        if ($r) {
            return $r;
        } else {
            return false;
        }
    }

    public function findCase($ticketId)
    {
        $method = "FindCase";

        $data = [
                "ticketid" => $ticketId
            ];

        $case = $this->makeCall($method, $data);

        if ($case) {
            return $case[0];
        } else {
            return false;
        }
    }

    public function getCases()
    {
        $method = "GetCases";
            
        return $this->makeCall($method, []);
    }

    public function getAppointments()
    {
        $method = "GetAppointments";
            
        return $this->makeCall($method, []);
    }

    public function getProducts()
    {
        $method = "GetProducts";
            
        return $this->makeCall($method, []);
    }

    public function makeQuoteRequest($customerNumber, $productName, $quantity)
    {
        $method = "QuoteRequest";

        $data = [
                'custno' => $customerNumber,
                'product'   => $productName,
                'quantity'   => intval($quantity)
            ];

        $r = $this->makeCall($method, $data);

        if ($r) {
            return true;
        } else {
            return false;
        }
    }


    // STAFF FUNCTIONS ////////////////

    public function staffLogin($username, $password)
    {
        $method = "Login";

        $data = [
                'username' => $username,
                'password'   => $password
            ];

        $r = $this->makeCall($method, $data);

        if ($r) {
            return true;
        } else {
            return false;
        }
    }

    public function staffDetails($username)
    {
        $method = "GetUserDetails";

        $data = [
                'username' => $username
            ];

        $r = $this->makeCall($method, $data);

        if ($r) {
            return $r[0];
        } else {
            return false;
        }
    }

    public function getStaffDetails($username, $password)
    {
        if (!$this->staffLogin($username, $password)) {
            return false;
        }
        $name = $this->staffDetails($username);

        if ($name) {
            return [
                    "name"=>$name->StaffName,
                    "username"=>$username,
                ];
        } else {
            return false;
        }
    }

    public function getStaffCases($staffUsername)
    {
        $method = "StaffGetCases";

        $data = [
                "username"=>$staffUsername
            ];
            
        return $this->makeCall($method, $data);
    }

    public function staffCreateCase($customerNumber, $description, $title, $staffUsername)
    {
        $method = "StaffCreateCase";

        $data = [
                "accountnumber" => $customerNumber,
                "description" => $description,
                "title" => $title,
                "username" => $staffUsername,
            ];

        return $this->makeCall($method, $data);
    }

    public function getAccounts($staffUsername)
    {
        $method = "GetAccounts";

        $data = [
                'username'=>$staffUsername
            ];
            
        return $this->makeCall($method, $data);
    }
        
    public function findAccount($acctNumber)
    {
        $method = "FindAccount";

        $data = [
                "acctnumber"=>$acctNumber
            ];
            
        $account = $this->makeCall($method, $data);
            
        if ($account) {
            return $account[0];
        } else {
            return false;
        }
    }

    public function createAccount($accountName, $email, $phone, $salesPerson, $country, $state, $acctType, $custClass, $custCategory, $origin, $department, $branch, $bizSector, $genbizgrp, $custPostGrp, $vatPostGrp, $staffUsername)
    {
        $method = "CreateAccount";

        $data = [
                "accountName"=>$accountName,
                "email"=>$email,
                "phone"=>$phone,
                "salesperson"=>$salesPerson,
                "country"=>$country,
                "state"=>$state,
                "acctType"=>$acctType,
                "custClass"=>$custClass,
                "custCategory"=>$custCategory,
                "origin"=>$origin,
                "department"=>$department,
                "branch"=>$branch,
                "bizSector"=>$bizSector,
                "genbizgrp"=> $genbizgrp,
                "custPostGrp"=>$custPostGrp,
                "vatPostGrp"=>$vatPostGrp,
                "username"=>$staffUsername
            ];

        return $this->makeCall($method, $data);
    }

    public function getLeads($staffUsername)
    {
        $method = "GetLeads";

        $data = [
                'username'=>$staffUsername
            ];
            
        return $this->makeCall($method, $data);
    }
        
    public function findLead($fullname)
    {
        $method = "FindLead";

        $data = [
                "fullname"=>$fullname
            ];
            
        $lead = $this->makeCall($method, $data);
            
        if ($lead) {
            return $lead[0];
        } else {
            return false;
        }
    }

    public function createLead($companyName, $firstname, $lastname, $category, $bizSector, $email, $bizPhone, $mobilePhone, $department, $dimension, $branch, $state, $prodOrService, $purchaseDate, $saleOrServiceMarketer, $staffUsername)
    {
        $method = "CreateLead";

        $purchaseDate = new \Carbon\Carbon($purchaseDate);

        $data = [
                "companyName"=>$companyName,
                "firstname"=>$firstname,
                "lastname"=>$lastname,
                "category"=>$category,
                "bizSector"=>$bizSector,
                "email"=>$email,
                "bizPhone"=>$bizPhone,
                "mobilePhone"=>$mobilePhone,
                "department"=>$department,
                "dimension"=>$dimension,
                "branch"=>$branch,
                "state"=>$state,
                "prodOrService"=>$prodOrService,
                "purchaseDate"=> $purchaseDate->toW3cString(),
                "saleOrServiceMarketer"=>$saleOrServiceMarketer,
                "username"=>$staffUsername
            ];

        return $this->makeCall($method, $data);
    }

    public function getOpportunities($staffUsername)
    {
        $method = "GetOpportunities";

        $data = [
                "username" => $staffUsername
            ];
            
        return $this->makeCall($method, $data);
    }

    //NOTE: Method does not exist
    public function findOpportunity($topic)
    {
        $method = "FindOpportunity";

        $data = [
                "topic"=>$topic
            ];
            
        $opportunity = $this->makeCall($method, $data);
            
        if ($opportunity) {
            return $opportunity[0];
        } else {
            return false;
        }
    }

    public function createOpportunity($topic, $customerNumber, $dimension, $estCloseDate, $probability, $rating, $salesperson, $description, $staffUsername)
    {
        $method = "CreateOpportuinity";

        $estCloseDate = new \Carbon\Carbon($estCloseDate);

        $data = [
                "topic"=>$topic,
                "customer"=>$customerNumber,
                "dimension"=>$dimension,
                "estCloseDate"=> $estCloseDate->toW3cString(),
                "probability"=>intval($probability),
                "rating"=>$rating,
                "salesperson"=>$salesperson,
                "description"=>$description,
                "username"=>$staffUsername
            ];

        return $this->makeCall($method, $data);
    }

    public function getContacts($staffUsername)
    {
        $method = "GetContacts";

        $data = [
                "username"=>$staffUsername
            ];
            
        return $this->makeCall($method, $data);
    }

    public function findContact($contactFullName)
    {
        $method = "FindContact";

        $data = [
                "fullname"=>$contactFullName
            ];
            
        $contact = $this->makeCall($method, $data);
            
        if ($contact) {
            return $contact[0];
        } else {
            return false;
        }
    }

    public function createContact($firstname, $lastname, $email, $bizPhone, $staffUsername)
    {
        $method = "CreateContact";

        $data = [
                "firstname"=>$firstname,
                "lastname"=>$lastname,
                "email"=>$email,
                "bizPhone"=>$bizPhone,
                "username"=>$staffUsername
            ];

        return $this->makeCall($method, $data);
    }

    public function getQuotes($staffUsername)
    {
        $method = "StaffGetQuotes";

        $data = [
                "username" => $staffUsername
            ];
            
        return $this->makeCall($method, $data);
    }

    public function findQuote($quoteNumber)
    {
        $method = "FindQuote";

        $data = [
                "number"=>$quoteNumber
            ];
            
        $quote = $this->makeCall($method, $data);
            
        if ($quote) {
            return $quote[0];
        } else {
            return false;
        }
    }

    public function createQuote($quoteName, $priceListName, $dimension, $department, $salesPerson, $branch, $customerNumber, $staffUsername)
    {
        $method = "CreateQuote";

        $data = [
                "name"=>$quoteName,
                "priceList"=>$priceListName,
                "dimension"=>$dimension,
                "department"=>$department,
                "salesperson"=>$salesPerson,
                "branch"=>$branch,
                "customer"=>$customerNumber,
                "username"=>$staffUsername
            ];

        return $this->makeCall($method, $data);
    }

    public function getOrders($staffUsername)
    {
        $method = "StaffGetOrders";

        $data = [
                "username"=>$staffUsername
            ];
            
        return $this->makeCall($method, $data);
    }

    public function findOrder($orderNumber)
    {
        $method = "FindOrder";

        $data = [
                "number"=>$orderNumber
            ];
            
        $order = $this->makeCall($method, $data);

        if ($order) {
            return $order[0];
        } else {
            return false;
        }
    }

    public function createOrder($orderName, $customerNumber, $dimension, $priceListName, $department, $branch, $priceIncludeVat, $staffUsername)
    {
        $method = "CreateOrder";

        $data = [
                "name"=>$orderName,
                "customer"=>$customerNumber,
                "dimension"=>$dimension,
                "priceList"=>$priceListName,
                "department"=>$department,
                "branch"=>$branch,
                "priceIncludeVat"=>$priceIncludeVat,
                "username"=>$staffUsername
            ];

        return $this->makeCall($method, $data);
    }

    public function findProduct($productName)
    {
        $method = "FindProduct";

        $data = [
                "productname"=>$productName
            ];
            
        $product = $this->makeCall($method, $data);

        if ($product) {
            return $product[0];
        } else {
            return false;
        }
    }

    public function getStaffAppointments($staffUsername)
    {
        $method = "StaffGetAppointments";

        $data = [
                "username"=>$staffUsername
            ];
            
        return $this->makeCall($method, $data);
    }

    //NOTE: works, both doesn't reflect in list
    public function createAppointment($customerNumber, $subject, $dimension, $startTime, $endTime, $staffUsername)
    {
        $method = "CreateServiceAppointment";

        $startTime = new \Carbon\Carbon($startTime);
        $endTime = new \Carbon\Carbon($endTime);
        $data = [
                "regarding" => $customerNumber,
                "subject" => $subject,
                "dimension" => $dimension,
                "StartTime" => $startTime->toW3cString(),
                "EndTime" => $endTime->toW3cString(),
                "username" => $staffUsername,
            ];

        return $this->makeCall($method, $data);
    }

    public function createCustomerAppointment($customerNumber, $subject, $dimension, $startTime, $endTime, $apptype, $vehicleregno, $servicetype, $mileage, $lastservicedate, $callpreference)
    {
        $method = "CustomerCreateApp";

        $startTime = new \Carbon\Carbon($startTime);
        $endTime = new \Carbon\Carbon($endTime);

        $lastservicedate = new \Carbon\Carbon($lastservicedate);
        $data = [
                "regarding" => $customerNumber,
                "subject" => $subject,
                "dimension" => $dimension,
                "StartTime" => $startTime->toW3cString(),
                "EndTime" => $endTime->toW3cString(),
                "apptype" => $apptype,
                "vehicleregno" => $vehicleregno,
                "servicetype" => $servicetype,
                "mileage" => $mileage,
                "lastservicedate" => $lastservicedate->toW3cString(),
                "callpreference" => $callpreference,
            ];

        return $this->makeCall($method, $data);
    }

    public function createCars($customerNumber, $regNo, $model, $year)
    {
        $method = "CreateCars";
        $data = [
            "custno" => $customerNumber,
            "regNo" => $regNo,
            "model" => $model,
            "year" => $year,
        ];

        return $this->makeCall($method, $data);
    }
}
