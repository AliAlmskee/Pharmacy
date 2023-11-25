<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class PharmacistMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $id = Auth::id();
        $user = User::find($id);
        if ($user->role === 'Pharmacist') {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
