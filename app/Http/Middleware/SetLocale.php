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
        // 1- لو الـ Session فيها لغة، نستخدمها هي الأول
        if (session()->has('locale')) {
            $locale = session()->get('locale');
            App::setLocale($locale);
            
            // لو المستخدم مسجل دخول ولغته في قاعدة البيانات مختلفة، نحدثها لتكون متناسقة
            if (Auth::check() && Auth::user()->language !== $locale) {
                try {
                    $user = Auth::user();
                    $user->language = $locale;
                    $user->save();
                } catch (\Exception $e) {
                    // تفادي أي خطأ لو العمود غير موجود أو مشكلة في الحفظ
                }
            }
        }
        // 2- لو مفيش في الـ Session بس مسجل دخول، نجيب من قاعدة البيانات
        else if (Auth::check()) {
            $userLang = Auth::user()->language ?? 'en';
            if (empty($userLang)) {
                $userLang = 'en';
            }
            App::setLocale($userLang);
            session()->put('locale', $userLang);
        }
        // 3- افتراضي انجليزي
        else {
            App::setLocale('en');
        }

        return $next($request);
    }
}