<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class AccountController extends Controller
{
    /** page account profile */
    public function profileDetail($user_id)
    {
        $profileDetail = User::with(['profile', 'jobInfo.jobTitle', 'jobInfo.department', 'hiringInfo', 'salary', 'insurance', 'documents'])
            ->where('user_id', $user_id)->first();
        return view('pages.account-profile', compact('profileDetail'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,user_id'
        ]);

        try {
            $user = User::where('user_id', $request->user_id)->first();
            
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && file_exists(public_path('assets/images/user/' . $user->avatar))) {
                    unlink(public_path('assets/images/user/' . $user->avatar));
                }

                $avatarName = time() . '_' . $user->name . '.' . $request->avatar->extension();
                $request->avatar->move(public_path('assets/images/user'), $avatarName);

                $user->avatar = $avatarName;
                $user->save();

                // Update session if it's the logged-in user
                if (auth()->id() == $user->id) {
                    session()->put('avatar', $avatarName);
                }

                return response()->json([
                    'success' => true,
                    'message' => __('messages.avatar_updated'),
                    'avatar_url' => asset('assets/images/user/' . $avatarName)
                ]);
            }

            return response()->json(['success' => false, 'message' => 'No file uploaded']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female',
            'experience_years' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            $user = User::where('user_id', $request->user_id)->firstOrFail();

            // Security check: Only the user themselves or Admin/HR can update
            if (Auth::user()->id !== $user->id && !Auth::user()->hasAnyRole(['Admin', 'HR'])) {
                return back()->with('error', 'Unauthorized action.');
            }

            // Update User table
            $user->name = $request->name;
            $user->phone_number = $request->phone_number;
            
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();

            // Update or Create EmployeeProfile
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'address' => $request->address,
                    'gender' => $request->gender,
                    'experience_years' => $request->experience_years,
                    'location' => $request->location,
                ]
            );

            // Update session if it's the logged-in user
            if (Auth::id() == $user->id) {
                session()->put('name', $user->name);
            }

            return back()->with('success', __('messages.profile_updated_success'));

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
