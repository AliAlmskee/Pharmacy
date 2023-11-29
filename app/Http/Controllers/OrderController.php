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

    public function destroy(Order $order)
    {
        $order->delete() ;

        return response(null , 204) ;
    }
}
