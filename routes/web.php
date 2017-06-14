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

Route::get('/phpinfo', function(){
    phpinfo();
});

#737Aviation
#Aviation737
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

    Route::post("/leads", "StaffController@createLead");    
    Route::get("/leads", "StaffController@getLeads");    
    Route::get("/leads/{account_number}", "StaffController@findLead");   

    Route::post("/accounts", "StaffController@createAccount");    
    Route::get("/accounts", "StaffController@getAccounts");    
    Route::get("/accounts/{lead_fullname}", "StaffController@findAccount");   

    Route::post("/cases", "StaffController@createCase");    
    Route::get("/cases", "StaffController@getCases");    
    Route::get("/cases/{ticket_id}", "CaseController@getCase");

    Route::post("/appointments", "StaffController@createAppointment");    
    Route::get("/appointments", "StaffController@getAppointments");    

    Route::post("/opportunities", "StaffController@createOpportunity");    
    Route::get("/opportunities", "StaffController@getOpportunities");    
    // Route::get("/opportunities/{topic}", "StaffController@findOpportunity");    

    Route::post("/contacts", "StaffController@createContact");    
    Route::get("/contacts", "StaffController@getContacts");    
    Route::get("/contacts/{contact_name}", "StaffController@findContact");    

    Route::post("/quotes", "StaffController@createQuote");    
    Route::get("/quotes", "StaffController@getQuotes");    
    Route::get("/quotes/{quote_number}", "StaffController@findQuote");    

    Route::post("/orders", "StaffController@createOrder");    
    Route::get("/orders", "StaffController@getOrders");    
    Route::get("/orders/{order_number}", "StaffController@findOrder");    

    Route::get("/products", "ProductController@getProducts");    
    // TODO: Product names can have slash, think of fix
    Route::get("/products/{product_name}", "ProductController@findProduct");    
});

Route::get('/try', function(){
    $sp = new SoapConnect();
    // $p = $sp->customerExist("CUST013643");
    $p = $sp->getStaffAppointments("Nav");
    // $p = $sp->createAppointment("CUST013643", "TEST ARES", "Aftersales", "4/4/2019 10:11:11", "4/4/2019 11:11:11", "Nav");

    return response()->json($p, 200);
});