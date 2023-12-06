<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\WareHouseController;
use App\Http\Controllers\MedicineWarehouseController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoritController;


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/getid', [AuthController::class, 'getAuthenticatedUserId']);
    Route::apiResource('order' , OrderController::class);
    Route::get('/search2', [MedicineController::class, 'search2']);

    Route::group([
        'middleware' => 'pharmacist' ,
    ] , function(){

        Route::apiResource('favorit' , FavoritController::class);

    });
        Route::post('/take_order', [OrderController::class, 'take_order']);

    Route::group([
        'middleware' => 'admin' ,
    ] , function(){

        Route::post('/phregister', [AuthController::class, 'phregister']);
        Route::apiResource('medicine_wareHouse' , MedicineWarehouseController::class);
        Route::get('/getAmount', [MedicineWarehouseController::class, 'getAmount']);


    });

});

Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('company' , CompanyController::class);
Route::get('company/{company}/medicines' , [CompanyController::class , 'medicinesByCompany']);

Route::apiResource('category' , CategoryController::class);
Route::get('category/{category}/medicines' , [CategoryController::class , 'medicinesByCategory']);

Route::apiResource('medicine' , MedicineController::class);
Route::post('/medicine/search' , [MedicineController::class , 'search2']) ; //if get then errore

Route::apiResource('warehouse' , WareHouseController::class);


