<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Session;

class AccountController extends Controller
{
    /** page account profile */
    public function profileDetail($user_id)
    {
        $profileDetail = User::with(['profile', 'jobInfo.jobTitle', 'jobInfo.department', 'hiringInfo', 'salary', 'insurance', 'documents', 'evaluations'])
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
}
