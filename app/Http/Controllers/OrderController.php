<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;

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
            'name'=> $request->name,
            'status'=> $request->status,
            'total_price'=> $request->total_price,
            'date'=> $request->date,
            'paid'=> $request->paid,
        ]);

        foreach($request->medicines as $med){
            $order->medicines()->attach($med['id'] , ['amount' => $med['amount']]) ;
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
        $order->delete() ;

        return response(null , 204) ;
    }

    //   لما ادمن يحذف الطلب

    //to do
    //public function delete Order



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


    public function take_order(Order $order)
    {
        if($order->status !='pending'){
        return response()->json('alrady taken ')     ;
         }
        $order->status = 'in_progress';
        $order->warehouse_id = Auth::user()->warehouse_id;

        $total =0 ;
        $medicines = $order->medicines() ;
        foreach($medicines as $medicure){
            $total += $medicure->price;

        }
        $order->total = $total ;
        $order->save();




    }
}
