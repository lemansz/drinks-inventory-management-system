<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $setting = $user->settings;
        
        return view('settings.create', compact('user', 'setting'));
    }

    public function updatePassword(Request $request)
    {
        $validatedData = $request->validate([
            'current-password' => 'required|string|regex:/^\d{6}$/',
            'new-password' => 'required|string|regex:/^\d{6}$/',
            'confirm-password' => 'required|string|same:new-password'
        ], [
            'current-password.required' => 'Current password is required.',
            'current-password.regex' => 'Current password must be exactly 6 digits.',
            'new-password.required' => 'New password is required.',
            'new-password.regex' => 'New password must be exactly 6 digits.',
            'confirm-password.required' => 'Password confirmation is required.',
            'confirm-password.same' => 'The password confirmation does not match.'
        ]);

        $user = auth()->user();

        // Verify current password
        if (!Hash::check($validatedData['current-password'], $user->password)) {
            return back()->withErrors([
                'current-password' => 'Your current password is incorrect.'
            ])->withInput($request->except('current-password', 'new-password', 'confirm-password'));
        }

        // Check if new password is different from current password
        if (Hash::check($validatedData['new-password'], $user->password)) {
            return back()->withErrors([
                'new-password' => 'New password must be different from your current password.'
            ])->withInput($request->except('current-password', 'new-password', 'confirm-password'));
        }

        // Update password
        $user->update([
            'password' => Hash::make($validatedData['new-password'])
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function updateEmail(Request $request)
    {
        $validatedData = $request->validate([
            'new-email' => 'required|email|unique:users,email',
            'confirm-email' => 'required|email|same:new-email',
            'email-password' => 'required|string|regex:/^\d{6}$/'
        ], [
            'new-email.required' => 'New email is required.',
            'new-email.email' => 'Please enter a valid email address.',
            'new-email.unique' => 'This email is already in use.',
            'confirm-email.required' => 'Email confirmation is required.',
            'confirm-email.same' => 'The email confirmation does not match.',
            'email-password.required' => 'Password is required.',
            'email-password.regex' => 'Password must be exactly 6 digits.'
        ]);

        $user = auth()->user();

        // Verify password
        if (!Hash::check($validatedData['email-password'], $user->password)) {
            return back()->withErrors([
                'email-password' => 'Your password is incorrect.'
            ])->withInput($request->except('email-password', 'new-email', 'confirm-email'));
        }

        // Check if new email is the same as current email
        if ($validatedData['new-email'] === $user->email) {
            return back()->withErrors([
                'new-email' => 'New email must be different from your current email.'
            ])->withInput($request->except('email-password', 'new-email', 'confirm-email'));
        }

        // Update email
        $user->update([
            'email' => $validatedData['new-email']
        ]);

        return back()->with('success', 'Email updated successfully!');
    }

    public function forgotPassword(Request $request)
    {
        $validatedData = $request->validate([
            'forgot-email' => 'required|email|exists:users,email'
        ], [
            'forgot-email.required' => 'Email is required.',
            'forgot-email.email' => 'Please enter a valid email address.',
            'forgot-email.exists' => 'This email is not registered in our system.'
        ]);

        $user = \App\Models\User::where('email', $validatedData['forgot-email'])->first();

        // Generate a temporary password (6 digits)
        $tempPassword = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update user with temporary password
        $user->update([
            'password' => Hash::make($tempPassword)
        ]);

        // TODO: Send email with temporary password
        // You can use Mail facade to send this when you have email configured
        // Uncomment the code below when ready to send emails
        // Mail::send('emails.forgot-password', ['user' => $user, 'tempPassword' => $tempPassword], function($m) use ($user) {
        //     $m->to($user->email)->subject('Your Temporary Password');
        // });

        return back()->with('success', 'Your password has been reset. Temporary password: ' . $tempPassword . '. Please change it after logging in.');
    }

    public function inventoryPreference(Request $request)
    {
        $validatedData = $request->validate([
            'currency' => 'required|string|max:1|in:$,€,£,¥,₦',
            'low_stock_threshold' => 'required|int|min:5|max:1000',
        ], [
            'currency.in' => 'Please select a valid currency from the list.',
            'low_stock_threshold.min' => 'Low stock threshold must be at least 5',
            'low_stock_threshold.max' => 'Low stock threshold cannot exceed 1000',
        ]);

        
        $setting = Setting::find(1);

        if (!$setting)
        {
            $setting = Setting::create([
                'currency' => '₦',
                'low_stock_threshold' => 10
            ]);
        }

        $setting->update($validatedData);

        return redirect()->back()->with('success', 'Stock preference update successfully!');
        
    }
}
