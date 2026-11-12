<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LandlordMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->role !== 'landlord') {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Only landlords can access this resource.'
            ], 403);
        }

        return $next($request);
    }
}
