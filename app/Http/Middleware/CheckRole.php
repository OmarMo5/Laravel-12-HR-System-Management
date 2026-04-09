<?php
// app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $userRole = Session::get('role_name');
        
        if (!$userRole) {
            return redirect('/login');
        }
        
        if (in_array($userRole, $roles)) {
            return $next($request);
        }
        
        abort(403, 'You do not have permission to access this page.');
    }
}