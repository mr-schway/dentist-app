<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CheckIfApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && !$user->is_approved) {
            Auth::logout();

            return redirect()->route('login')
                ->withErrors(['email' => 'Your account is not approved yet. Please wait for admin approval.']);
        }

        return $next($request);
    }
}
