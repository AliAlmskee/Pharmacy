<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicineWarehouseController extends Controller
{
    public function all()//for super admin
    {

    }
    public function index(Request $request)
    {
        $warehouseId = $request->input('warehouse_id');

        $warehouse = Warehouse::find($warehouseId);

        if( $warehouse){
        $medicines = $warehouse->medicines()->withPivot('amount', 'final_date')->get();
        return response()->json($medicines);

        }
        return response()->json($warehouse );

    }
    public function medicine(Medicine $medicine)//for all warehouses
    {

    }

    public function warehouse(Warehouse $warehouse , Medicine $medicine)
    {

    }
        public function store(Request $request)//admin store the medicine amount in his warehouse
        {
            // $warehouseId = Auth::user()->warehouse_id;
            $warehouseId = 1;
            $medicineId = $request->input('medicine_id');
            $amount = $request->input('amount');
            $finalDate = $request->input('final_date');

            $medicine = Medicine::find($medicineId);
            $warehouse = Warehouse::find($warehouseId);

            if(!$medicine or !$warehouse){
                return response()->json([
                    'error' => ' one or two not found  ' ,
                ] , 400) ;
            }

            $warehouse->medicines()->attach($medicineId, [
                'amount' => $amount,
                'final_date' => $finalDate,
            ]);

            return response()->json('Data stored successfully.');
        }
    public function destroy(Medicine $medicine)
    {

    }



    public function getAmount(Request $request)
    {
        $medicineId = $request->query('medicine_id');
        $warehouseId = $request->query('warehouse_id');

        $warehouse = Warehouse::find($warehouseId);
        $medicines = $warehouse->medicines->where('id', $medicineId);

        $total = 0;
        foreach ($medicines as $medicine) {
            $total += $medicine->pivot->amount;
        }

        return response()->json($total);
    }


    //need testing
    public function moveMedicine(Request $request)
    {
        $medicineId = $request->medicine_id;
        $warehouseId = $request->warehouse_id;
        $amount = $request->amount;

        $admin = Auth::user();
        $sourceWarehouse = Warehouse::find($warehouseId);
        $destinationWarehouse = Warehouse::find($admin->$warehouseId);

        $medicinesInSource = $sourceWarehouse->medicines()->where('medicine_id', $medicineId)->withPivot('amount', 'final_date')->get();
        $closest = $medicinesInSource->first();

        foreach ($medicinesInSource as $medicine) {
            if ($medicine->pivot->final_date < $closest->pivot->final_date) {
                $closest = $medicine;
            }
        }

        $closest->pivot->amount -= $amount;
        $closest->pivot->save();

        $medicineInDestination = $destinationWarehouse->medicines()->where('medicine_id', $medicineId)->first();

        if ($medicineInDestination) {
            $medicineInDestination->pivot->amount += $amount;
            $medicineInDestination->pivot->save();
        } else {
            $destinationWarehouse->medicines()->attach($medicineId, ['amount' => $amount, 'final_date' => $closest->pivot->final_date]);
        }

    }


    }


