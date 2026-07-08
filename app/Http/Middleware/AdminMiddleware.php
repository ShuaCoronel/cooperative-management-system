<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;



// cline fix 
// >>> [CREATED] Middleware to restrict route access to Admin users only
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role !== UserRole::ADMIN) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return $next($request);
    }
}