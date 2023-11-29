<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\WareHouseController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/getid', [AuthController::class, 'getAuthenticatedUserId']);

    Route::group([
        'meddleware' => 'pharmacist' ,
    ] , function(){

    });

    Route::group([
        'meddleware' => 'admin' ,
    ] , function(){

    });

});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('company' , CompanyController::class);
Route::get('company/{company}/medicines' , [CompanyController::class , 'medicinesByCompany']);

Route::apiResource('category' , CategoryController::class);
Route::get('category/{category}/medicines' , [CategoryController::class , 'medicinesByCategory']);

Route::apiResource('medicine' , MedicineController::class);
Route::get('medicine/search/{name}' , [MedicineController::class , 'search']) ;

Route::apiResource('warehouse' , WareHouseController::class);
