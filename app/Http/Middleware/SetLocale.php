<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1- لو المستخدم مسجل دخول، نجيب اللغة من قاعدة البيانات
        if (Auth::check()) {
            $userLang = Auth::user()->language ?? 'en';
            App::setLocale($userLang);
            session()->put('locale', $userLang);
        }
        // 2- لو مش مسجل دخول، نشوف الـ Session
        else if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        }
        // 3- افتراضي انجليزي
        else {
            App::setLocale('en');
        }

        return $next($request);
    }
}