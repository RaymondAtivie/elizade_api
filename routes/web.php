<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Factories\SoapConnect;

Route::get('/', function () {
    return view('welcome');
});

Route::get('try', function(){
    phpinfo();
});


Route::post("/login", "AuthController@login");
Route::post("/signup", "AuthController@signup");

Route::post("/loginstaff", "AuthController@loginStaff");

//GENERAL
Route::get("/countries", "GeneralController@getCountries");
Route::get("/states", "GeneralController@getStates");
Route::get("/departments", "GeneralController@getDepartments");
Route::get("/branches", "GeneralController@getBranches");
Route::get("/businesssectors", "GeneralController@getBusinessSectors");
Route::get("/genpostinggroup", "GeneralController@getGenBizPostingGrp");
Route::get("/custpostinggroup", "GeneralController@getCustPostingGr");
Route::get("/vatpostinggroup", "GeneralController@getVatPostingGrp");
Route::get("/pricelist", "GeneralController@getPricelists");
Route::get("/salespersons", "GeneralController@getSalespersons");

Route::group(['middleware' => 'auth'], function() {
    Route::get("/products", "ProductController@getProducts");

    Route::post("/quotes", "OrderController@makeQuoteRequest");

    Route::get("/appointments", "CaseController@getAppointments");

    Route::get("/cases", "CaseController@getCases");
    Route::get("/cases/{ticket_id}", "CaseController@getCase");
    Route::post("/cases", "CaseController@createCase");
});


Route::group(['middleware' => 'auth.staff', "prefix"=>"staff"], function() {
    Route::get("/cases", "StaffController@getCases");    
    Route::get("/appointments", "StaffController@getAppointments");    
    // Route::get("/opportunities", "StaffController@getOpportunities");    
    Route::get("/contacts", "StaffController@getOpportunities");    
    Route::get("/quotes", "StaffController@getQuotes");    
    Route::get("/orders", "StaffController@getOrders");    
    Route::get("/products", "ProductController@getProducts");    
});

Route::get('/try', function(){
    $sp = new SoapConnect();
    // $p = $sp->customerExist("CUST013643");
    // $p = $sp->getStaffDetails("Nav", "elizade");
    // $p = $sp->staffDetails("Nav");
    // $p = $sp->FindCase("CAS-00037-N0F8S0");
    // $p = $sp->getCases();
    // $p = $sp->getProducts();
    // $p = $sp->makeQuoteRequest("CUST013643", "Toyota Hilux 4WD DC AC D", "3");
    // $p = $sp->getAppointments();
    // $p = $sp->createCase("CUST013643", "Sample Title", "Sample Description");
    // $p = $sp->showMethods();
    $p = $sp->getProduct();

    return response()->json($p, 200);
});