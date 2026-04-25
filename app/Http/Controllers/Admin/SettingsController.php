<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function edit()
    {
        // Fetch all settings as key-value pairs for easy access in view
        $settings = Setting::all()->pluck('value', 'key');

        return view('admin.settings', [
            'user' => auth()->user(),
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = auth()->user();
        
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->setRememberToken(Str::random(60));
        
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateGeneral(Request $request)
    {
        // Validate specific fields if necessary
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'invoice_template' => 'nullable|string|in:default,modern',
            // Add other validations as needed
        ]);

        $data = $request->except(['_token', '_method']);
        
        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Site settings updated successfully.');
    }
}
