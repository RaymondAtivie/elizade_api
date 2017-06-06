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

Route::group(['middleware' => 'auth'], function() {
    Route::get("/products", "ProductController@getProducts");

    Route::get("/appointments", "CaseController@getAppointments");

    Route::get("/cases", "CaseController@getCases");
    Route::get("/cases/{ticket_id}", "CaseController@getCase");
    Route::post("/cases", "CaseController@createCase");
});

Route::get('/try', function(){
    $sp = new SoapConnect();
    // $p = $sp->customerExist("CUST013643");
    // $p = $sp->FindCase("CAS-00037-N0F8S0");
    // $p = $sp->getCases();
    // $p = $sp->getProducts();
    // $p = $sp->makeQuoteRequest("CUST013643", "Toyota Hilux 4WD DC AC D", "3");
    // $p = $sp->getAppointments();
    // $p = $sp->createCase("CUST013643", "Sample Title", "Sample Description");
    $p = $sp->showMethods();

    return response()->json($p, 200);
});