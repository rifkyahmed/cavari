<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // Generate 6-digit code
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Log for debugging (Important for local dev)
        Log::info("Password Reset Code for {$request->email}: $code");

        // Store hashed code in password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($code), 'created_at' => now()]
        );

        // Send Email
        try {
            Mail::raw("Your password reset code is: $code", function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Password Reset Code');
            });
        } catch (\Exception $e) {
            // Log error or ignore in dev
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'We have emailed your password reset code.']);
        }

        return back()->with('status', 'We have emailed your password reset code.');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string'
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if ($record && Hash::check($request->code, $record->token)) {
             return response()->json(['success' => true, 'message' => 'Code verified.']);
        }

        return response()->json(['message' => 'The provided code is invalid.', 'errors' => ['code' => ['Invalid code.']]], 422);
    }

    public function resetWithCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->code, $record->token)) {
             return response()->json(['message' => 'Invalid code or expired.', 'errors' => ['code' => ['Invalid code.']]], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
             return response()->json(['message' => 'User not found.'], 404);
        }

        // Update Password
        $user->forceFill([
            'password' => Hash::make($request->password)
        ])->setRememberToken(Str::random(60));
        $user->save();

        event(new PasswordReset($user));

        // Delete Token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        // Auto Login
        Auth::login($user);

        return response()->json([
            'success' => true, 
            'message' => 'Password has been reset successfully.',
            'redirect' => route('dashboard', absolute: false)
        ]);
    }
}
