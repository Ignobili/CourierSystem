<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Middlewares:
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//USER MANAGEMENT
Route::post('register',                 [App\Http\Controllers\API\RegisterController::class,    'registerUser']);
Route::post('login',                    [App\Http\Controllers\API\LoginController::class,       'loginUser']);
Route::post('logout',                   [App\Http\Controllers\API\LogoutController::class,      'logout'])->middleware('auth:sanctum');
Route::post('users-list',               [App\Http\Controllers\API\UsersListController::class,   'getUsersList']);
Route::delete('user-removal/{id}',      [App\Http\Controllers\API\UsersListController::class,   'removeUser']);

//ESHOP MANAGEMENT
Route::post('eshop-list',               [App\Http\Controllers\API\EshopListController::class,   'getEshopList']);
Route::post('eshop-insert',             [App\Http\Controllers\API\EshopListController::class,   'insertEshop']);
Route::delete('eshop-delete/{id}',      [App\Http\Controllers\API\EshopListController::class,   'deleteEshop']);
Route::post('eshop-update/{id}',        [App\Http\Controllers\API\EshopListController::class,   'updateEshop']);

//COURIER MANAGEMENT
Route::post('courier-list',             [App\Http\Controllers\API\CourierListController::class, 'getCourierList']);
Route::post('courier-insert',           [App\Http\Controllers\API\CourierListController::class, 'insertCourier']);
Route::post('courier-update/{id}',      [App\Http\Controllers\API\CourierListController::class, 'updateCourier']);
Route::delete('courier-delete/{id}',    [App\Http\Controllers\API\CourierListController::class, 'deleteCourier']);

// RULE MANAGEMENT
Route::post('pravidla',                 [App\Http\Controllers\API\RuleController::class,        'resolve']);
Route::post('rule-list',                [App\Http\Controllers\API\RuleListController::class,    'getRuleList']);
Route::post('courier-service-list',     [App\Http\Controllers\API\RuleListController::class,    'getCourierServices']);
Route::post('predicate-list',           [App\Http\Controllers\API\RuleListController::class,    'getPredicate']);
Route::post('subject-list',             [App\Http\Controllers\API\RuleListController::class,    'getSubject']);
Route::post('rule-insert',              [App\Http\Controllers\API\RuleListController::class,    'insertRule']);
Route::post('rule-update/{id}',         [App\Http\Controllers\API\RuleListController::class,    'updateRule']);
Route::delete('rule-delete/{id}',       [App\Http\Controllers\API\RuleListController::class,    'deleteRule']);
Route::post('get-filtered-rules/{id}',  [App\Http\Controllers\API\RuleListController::class,    'getFilteredRules']);

//SERVICE MANAGEMENT
Route::post('service-list',             [App\Http\Controllers\API\ServicesController::class,    'getCourierServices']);
Route::post('service-insert',           [App\Http\Controllers\API\ServicesController::class,    'insertService']);
Route::post('service-update/{id}',      [App\Http\Controllers\API\ServicesController::class,    'updateService']);
Route::delete('service-delete/{id}',    [App\Http\Controllers\API\ServicesController::class,    'deleteService']);

//ANYTHING ELSE
