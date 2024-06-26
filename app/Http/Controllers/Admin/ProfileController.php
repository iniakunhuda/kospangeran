<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        return view('admin.profile.index', compact('user'));
    }


    public function update(Request $request)
    {
        // update password based user login
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $user = auth()->user();

        $dbUser = User::find($user->id);
        $dbUser->name = $request->name;
        $dbUser->email = $request->email;

        if (isset($request->password) && $request->password != '') {
            if($request->password != $request->password_confirmation){
                return redirect()->route('profile.index')->with('error', 'Password confirmation not match');
            }
            $dbUser->password = bcrypt($request->password);
        }

        $saved = $dbUser->save();

        if ($saved) {
            return redirect()->route('profile.index')->with('success', 'Profile updated successfully');
        } else {
            return redirect()->route('profile.index')->with('error', 'Profile failed to update');
        }
    }

}
