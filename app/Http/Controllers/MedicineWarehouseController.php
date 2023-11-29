<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class MedicineWarehouseController extends Controller
{
    public function all()//for super admin
    {

    }
    public function index()//for admin 
    {

    }
    public function medicine(Medicine $medicine)//for all warehouses
    {

    }

    public function warehouse(Warehouse $warehouse , Medicine $medicine)
    {

    }

    public function store(Medicine $medicine)//admin store the medicine amount in his warehouse
    {
        
    }

    public function destroy(Medicine $medicine)
    {
        
    }
}

