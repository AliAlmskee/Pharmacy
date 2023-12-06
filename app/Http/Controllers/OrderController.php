<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Models\Warehouse;

class OrderController extends Controller
{
    use HttpResponses;

    public function all(){
        return OrderResource::collection(Order::paginate());
    }
    public function index()
    {
        return OrderResource::collection(
            Order::where('user_id' , Auth::id())->latest()
        );
    }

    public function store(StoreOrderRequest $request)
    {

        $request->validated($request->all()) ;

        $order = Order::create([
            'user_id'=> Auth::id(),
            'status'=> 'pending',
            'total_price'=> $request->total_price,
            'date'=> $request->date,
            'paid'=>false,
        ]);

        foreach($request->medicines as $med){
            $order->medicines()->attach($med['id'] , ['medicine_amount' => $med['medicine_amount']]) ;
        }

        return new OrderResource($order);
    }

    public function show(Order $order)
    {
       return new OrderResource($order);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->all()) ;

        return new OrderResource($order);
    }


//   لما صيدلاني يحذف الطلب
    public function destroy(Order $order)
    {
        if($order->status=='pending'){
        $order->delete() ;

        return response(null , 204) ;}
        return response()->json(" the order alradu in progress ");
    }

    //   لما ادمن يحذف الطلب
    //to do
    //public function delete Order


    public function my_orders()
    {
        $orders = Order::where("user_id", Auth::id() )->where('status','in_progress')->get();
        return $orders ;


    }

    public function take_order(Request $request)
    {
        $order = Order::findOrFail($request->input('order_id'));
        $order->user_id = Auth::id();
        $order->warehouse_id = Auth::user()->warehouse_id;
        $order->status = 'in_progress';
        $order->save();


        $pivotData = $order->medicines()->get()->map(function ($medicine) {
            return $medicine->pivot;
        });

        $warehouseId = Auth::user()->warehouse_id;
        $response = [];
        foreach ($pivotData as $pivot) {
            $medicines = Warehouse::find($warehouseId)->medicines()
                ->where('medicine_id', $pivot['medicine_id'])
                ->orderBy('final_date', 'asc')
                ->withPivot('amount', 'final_date')
                ->get();
                $tolta_amount =0 ;
                $required_amount =  $pivot['medicine_amount'];
                foreach ($medicines as $medicine)
                {
                    $tolta_amount += $medicine->pivot->amount;
                }
                if ( $tolta_amount >= $required_amount) {
                         while($required_amount > 0)
                         {


                         }



                }
                else
                {

                    return response()->json(['message' => 'not enogh']);



                }
               $medicine->pivot->save();
                $response[] = [
                    'pivot' =>  $medicine->pivot
                ];

        }

        return response()->json($response);
    }


    public function status2on_the_way(Order $order)
    {
        $order->status = 'on_its_way';
        $order->save();
        return response()->json(" order 2 on_its_way .done!",200) ;

    }
    public function status2completed(Order $order)
    {
        $order->status = 'completed';
        $order->save();
        return response()->json(" order 2 completed .done!",200) ;

    }



}
