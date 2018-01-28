<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Factories\SoapConnect;

class GeneralController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCountries()
    {
        $SC = new SoapConnect();
        $result = $SC->getCountries();

        $data = [
            "success"=>true,
            "message"=>"successfully retrieved",
            "data"=>$result
        ];

        return response()->json($data, 200);
    }

    public function getStates()
    {
        $SC = new SoapConnect();
        $result = $SC->getStates();

        $data = [
            "success"=>true,
            "message"=>"successfully retrieved",
            "data"=>$result
        ];

        return response()->json($data, 200);
    }

    public function getDepartments()
    {
        $SC = new SoapConnect();
        $result = $SC->getDepartments();

        $data = [
            "success"=>true,
            "message"=>"successfully retrieved",
            "data"=>$result
        ];

        return response()->json($data, 200);
    }

    public function getBranches()
    {
        $SC = new SoapConnect();
        $result = $SC->getBranches();

        $data = [
            "success"=>true,
            "message"=>"successfully retrieved",
            "data"=>$result
        ];

        return response()->json($data, 200);
    }

    public function getBusinessSectors()
    {
        $SC = new SoapConnect();
        $result = $SC->getBusinessSectors();

        $data = [
            "success"=>true,
            "message"=>"successfully retrieved",
            "data"=>$result
        ];

        return response()->json($data, 200);
    }

    public function getGenBizPostingGrp()
    {
        $SC = new SoapConnect();
        $result = $SC->getGenBizPostingGrp();

        $data = [
            "success"=>true,
            "message"=>"successfully retrieved",
            "data"=>$result
        ];

        return response()->json($data, 200);
    }

    public function getCustPostingGr()
    {
        $SC = new SoapConnect();
        $result = $SC->getCustPostingGr();

        $data = [
            "success"=>true,
            "message"=>"successfully retrieved",
            "data"=>$result
        ];

        return response()->json($data, 200);
    }

    public function getVatPostingGrp()
    {
        $SC = new SoapConnect();
        $result = $SC->getVatPostingGrp();

        $data = [
            "success"=>true,
            "message"=>"successfully retrieved",
            "data"=>$result
        ];

        return response()->json($data, 200);
    }

    public function getPricelists()
    {
        $SC = new SoapConnect();
        $result = $SC->getPricelists();

        $data = [
            "success"=>true,
            "message"=>"successfully retrieved",
            "data"=>$result
        ];

        return response()->json($data, 200);
    }

    public function getSalespersons()
    {
        $SC = new SoapConnect();
        $result = $SC->getSalespersons();

        $data = [
            "success"=>true,
            "message"=>"successfully retrieved",
            "data"=>$result
        ];

        return response()->json($data, 200);
    }
    
    public function getCars()
    {
        $SC = new SoapConnect();
        $cars = $SC->getCars();

        $user = $this->auth->parseToken()->authenticate()->getUser();

        $myCars = [];
        foreach ($cars as $car) {
            if ($car->Customer == $user->customer_number) {
                if (!$this->isCarExist($myCars, $car->RegistrationNo)) {
                    $myCars[] = $car;
                }
            }
        }

        $data = [
            "success"=>true,
            "message"=>"successfully retrieved cars",
            "data"=>$myCars
        ];

        return response()->json($data, 200);
    }

    public function isCarExist($allCars, $regNo)
    {
        foreach ($allCars as $car) {
            if ($car->RegistrationNo == $regNo) {
                return true;
            }
        }

        return false;
    }
}
