<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Hash;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /** Show the registration page */
    public function register()
    {
        return view('auth.register');
    }


    /** Store a new user */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Generate a unique user_id for the new employee
            $latestUser = User::orderBy('id', 'DESC')->first();
            $userId     = $latestUser ? (int) substr($latestUser->user_id, 4) + 1 : 1;
            $employeeId = 'KH_' . str_pad($userId, 3, '0', STR_PAD_LEFT);

            $register = new User();
            $register->user_id      = $employeeId; // Add this line
            $register->name         = $request->name;
            $register->email        = $request->email;
            $register->join_date    = Carbon::now()->toDateString(); // Better to use date format
            $register->role_name    = 'User Normal';
            $register->status       = 'Active';
            $register->avatar       = 'profile.png'; // Default avatar
            $register->password     = Hash::make($request->password);
            $register->save();

            flash()->success('Account created successfully :)');
            return redirect('login');
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e);
            flash()->error('Failed to create account. Please try again.');
            return redirect()->back()->withInput();
        }
    }
}
