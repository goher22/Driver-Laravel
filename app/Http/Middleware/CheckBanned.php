<?php

namespace App\Http\Middleware;

use Closure;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
         if (auth()->check() && auth()->user()->banned) {
            $user = \App\User::find(auth()->user()->id);

            auth()->logout();
            
            //If the user is banned we don't want to audit this login and logout event
            //So we need to delete last two audit records after logout. 
            $user->audits()->latest()->first()->delete();
            $user->audits()->latest()->first()->delete();

            $message = __("Your account has been suspended. Please contact administrator.");

            return redirect()->route('login')->withMessage($message);
        }

        return $next($request);
    }
}
