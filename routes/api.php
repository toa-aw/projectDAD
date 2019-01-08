<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



//Authentication API routes
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');
Route::post('login', 'LoginControllerAPI@login');

//User API routes
Route::middleware('auth:api')->get('users/me', 'API\UserControllerAPI@myProfile');
Route::put('user/password/set/{token}', 'API\UserControllerAPI@setPassword');
Route::put('user/password/update/{id}', 'API\UserControllerAPI@updatePassword');
Route::post('photo/upload', 'API\PhotoControllerAPI@store');
Route::put('alter/shift/{id}', 'API\UserControllerAPI@alterShift');
Route::put('alter/block/{id}', 'API\UserControllerAPI@alterBlock')->middleware('auth:api');

//Order API routes
Route::get('/orders/cook/{id}', 'API\OrderControllerAPI@getCooksOrders');
Route::get('/orders/filter', 'API\OrderControllerAPI@filterOrders');
Route::get('/orders/unassigned', 'API\OrderControllerAPI@getUnassigned');
Route::put('/order/change/state/{id}', 'API\OrderControllerAPI@changeState');

//Meal APi routes
Route::get('/meals/waiter/{id}', 'API\MealControllerAPI@getWaitersMeals');

//Table API routes
Route::get('/tables/free', 'API\TableControllerAPI@getFreeTables');

//Invoice API routes
Route::get('/invoices/pending', 'API\InvoiceControllerAPI@getPendingInvoices');
Route::put('/invoices/{id}', 'API\InvoiceControllerAPI@update');
Route::get('/invoices/dowload/{id}', 'API\InvoiceControllerAPI@downloadPDF');

Route::apiResources([
    'items' => 'API\MenuControllerAPI',
    'users' => 'API\UserControllerAPI',
    'orders' => 'API\OrderControllerAPI',
    'meals' => 'API\MealControllerAPI',
    'tables' => 'API\TableControllerAPI',
    'invoices' => 'API\InvoiceControllerAPI'
]);

// ['middleware' => ['auth:api']]