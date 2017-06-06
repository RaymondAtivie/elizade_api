<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisaninweb\SoapWrapper\SoapWrapper;
use SoapClient;

class SoapController extends Controller{

  protected $soapWrapper;

  public function __construct(SoapWrapper $soapWrapper){
    $this->soapWrapper = $soapWrapper;
  }

    public function demo(){
        // Add a new service to the wrapper
        // $this->soapWrapper->add(function ($service) {
        //     $service->name('CRW')
        //             ->wsdl('http://crm.elizade.net:5050/Service1.svc?wsdl')
        //             ->trace(true);
        // });
        $this->soapWrapper->add('CRW', function ($service) {
            $service
                ->wsdl('http://crm.elizade.net:5050/Service1.svc?wsdl')
                ->trace(true);
                // ->classmap([
                //     GetConversionAmount::class,
                //     GetConversionAmountResponse::class,
                // ]);
        });

        $data = [
            'lastname' => 'Raymond',
            'firstname'   => 'Ativie',
            'email'   => 'raymondativie@gmail.com',
            'bizPhone' => '08151700676'
            ];

        $case = [
            'accountnumber' => 'CUST013643',
            'description'   => 'This is a description',
            'title'   => 'Hello world'
            ];

            // $response = $this->soapWrapper->call('CRW.CreateContact', [$data]);
            $response = $this->soapWrapper->call('CRW.CreateCase', [$case]);

            var_dump($response);

        // Using the added service
        // $this->soapWrapper->service('CRW', function ($service) use ($data) {
        //     var_dump($service->call('CreateContact', [$data]));
        //     // var_dump($service->call('Otherfunction'));
        // });
    }

    public function demo2(){

        $data = [
            'lastname' => 'Raymond',
            'firstname'   => 'Ativie',
            'email'   => 'raymondativie@gmail.com',
            'bizPhone' => '08151700676'
            ];

        try{
            $opts = array(
                'ssl' => array(
                    'ciphers'=>'RC4-SHA', 
                    'verify_peer'=>false, 
                    'verify_peer_name'=>false,
                    'allow_self_signed' => true,   
                'verifypeer' => false, 
                'verifyhost' => false,                  
                ),
                'http'=>array(
                    'user_agent' => 'PHPSoapClient'
                )
            );
            // SOAP 1.2 client
            $params = array (
                'encoding' => 'UTF-8', 
                'verifypeer' => false, 
                'verifyhost' => false, 
                'soap_version' => SOAP_1_2, 
                'trace' => 1, 'exceptions' => 1, "connection_timeout" => 180, 
                'stream_context' => stream_context_create($opts),
                'cache_wsdl' => WSDL_CACHE_NONE
            );

            // $wdsl = file_get_contents('wsdl/home.xml', false, stream_context_create($opts));

            // var_dump($wdsl);

            // die();

            // $context = stream_context_create($opts);
            $client = new \SoapClient('http://crm.elizade.net:5050/Service1.svc?wsdl',$params);

            $result = $client->CreateContact($data);
            print_r($result);
        }
        catch(Exception $e){
            echo "hail";
            echo $e->getMessage();
        }
    }

    public function demo3(){
        try {
            $client = new SoapClient("http://crm.elizade.net:5050/Service1.svc?wsdl");

        $case = [
            'accountnumber' => 'CUST013643',
            'description'   => 'This is a description',
            'title'   => 'Hello world'
            ];

        $retval = $client->CreateCase($case);

        var_dump($retval);

        } catch (Exception $e) {
            echo "<h1>Cannot connnect to Loan Application Server Right now. Please try again Later<h1>";
            die();
        }
    }

    public function demo4(){
        $wsdl = "http://crm.elizade.net:5050/Service1.svc?wsdl";
       
       try{

                $opts = array(
                    'http'=>array(
                        'user_agent' => 'PHPSoapClient'
                        )
                    );

                $context = stream_context_create($opts);
                $client = new SoapClient($wsdl,
                                        array('stream_context' => $context,
                                            'cache_wsdl' => WSDL_CACHE_NONE));

                $result = $client->CreateCase(array(
                    'accountnumber' => 'CUST013643',
                    'description'   => 'Test description for Raymond to confirm return',
                    'title'   => 'confirm return testTitle for raymond',
                    // 'bizPhone'   => '0949835443594'
                ));

                // $result = $client->GetAppointments();
                print_r($result);
            }
            catch(Exception $e){
                echo $e->getMessage();
            }
    }
}