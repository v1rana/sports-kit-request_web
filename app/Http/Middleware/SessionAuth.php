<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SessionAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
	{
		\Log::info('SessionAuth middleware called.');
\Log::info('Session: ', session()->all());

		if (!session()->has('user_id')) {
			return redirect()->route('login')->withErrors(['message' => 'Please log in first.']);
		}

		return $next($request);
	}

}
