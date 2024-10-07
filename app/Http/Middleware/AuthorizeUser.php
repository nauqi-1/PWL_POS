<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = ' '): Response
    {
        $user = $request->user(); //ambil data user yg login

        if($user->hasRole($role)) {  //cek apakah user punya role yang diinginkan
            return $next($request);
        }

        abort(403,'Forbidden. No Access for u >:3'); //tambah pesan error bagi user yang tidak memiliki level yg sesuai
    }
}
