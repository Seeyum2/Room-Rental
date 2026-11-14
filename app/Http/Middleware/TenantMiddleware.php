<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || $request->user()->role !== 'tenant') {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Only tenants can access this resource.'
            ], 403);
        }

        return $next($request);
    }
}