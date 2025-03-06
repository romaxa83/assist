<?php

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAdmin
{
    public function handle(Request $request, Closure $next)
    {

        dd('f');
        // Если пользователь не авторизован и запрашивает маршрут "admin"
        if (!Auth::check() && $request->is('admin*')) {
            return redirect('/admin/login'); // Перенаправляем на страницу входа
        }

        return $next($request);
    }
}
