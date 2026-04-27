<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Session;
use Auth;
use Illuminate\Support\Facades\App;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /** Show the login page */
    public function login()
    {
        return view('auth.login');
    }

    /** Authenticate the user */
    public function authenticate(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);
        
        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $todayDate = Carbon::now()->toDayDateTimeString();

                // جلب اللغة من قاعدة البيانات
                $userLanguage = $user->language ?? 'en';
                
                // تحديث اللغة في Session و App
                session()->put('locale', $userLanguage);
                App::setLocale($userLanguage);

                // Store user information in session
                Session::put([
                    'name'         => $user->name,
                    'email'        => $user->email,
                    'user_id'      => $user->user_id,
                    'join_date'    => $user->join_date,
                    'last_login'   => $todayDate,
                    'phone_number' => $user->phone_number,
                    'location'     => $user->location,
                    'status'       => $user->status,
                    'role_name'    => $user->role_name,
                    'avatar'       => $user->avatar,
                    'position'     => $user->position,
                    'department'   => $user->department,
                ]);
                
                // Update last login
                $user->update(['last_login' => $todayDate]);

                if ($user->role_name == 'Employee') {
                    flash()->success(__('messages.success_login'));
                    return redirect()->route('page/account', $user->user_id);
                }

                flash()->success(__('messages.success_login'));
                return redirect()->intended('home');
            } else {
                flash()->error(__('messages.email_invalid'));
                return redirect('login');
            }
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('An error occurred during login');
            return redirect()->back();
        }
    }

    /** Show logout page */
    public function logoutPage()
    {
        return view('auth.logout');
    }

    /** Logout and forget session */
    public function logout(Request $request)
    {
        // نأخذ اللغة الحالية قبل مسح Session
        $currentLanguage = session()->get('locale', 'en');
        
        // مسح الـ Session
        $request->session()->flush();
        
        // تسجيل الخروج
        Auth::logout();
        
        // نعيد اللغة تاني في Session الجديدة
        session()->put('locale', $currentLanguage);
        App::setLocale($currentLanguage);
        
        flash()->success(__('messages.sign_out'));
        return redirect('logout/page');
    }
}