<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreFavoriteRequest;
use Illuminate\Support\Facades\Auth;

class FavoritController extends Controller
{
    public function index(){
        $favorites = Auth::user()->favoriteMedicines()->select('commercial_name')->get();
        return response()->json(['message' =>  $favorites], 200);
    }



    public function store(StoreFavoriteRequest $request){

        Auth::user()->favoriteMedicines()->attach($request->medicin_id);
        return response()->json(['message' => 'Favorite medicine added successfully'], 200);
    }

    public function destroy($medicin_id)
    {
        Auth::user()->favoriteMedicines()->detach($medicin_id);
        return response()->json(['message' => 'Favorite medicine removed successfully'], 200);
    }



}
