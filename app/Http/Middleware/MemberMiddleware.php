<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MemberMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


        $user = Auth::user();
        if(!$user || $user->role != UserRole::MEMBER) {

            return redirect('login')->with('error','Please log in to your account');


        }
        
        return $next($request);
    }
}
