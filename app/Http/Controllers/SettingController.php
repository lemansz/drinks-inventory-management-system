<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateEmailRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\TemporaryPasswordMail;


class SettingController extends Controller
{
    public function create()
    {
        $user = request()->user();
        $setting = $user->settings;
        
        return view('settings.create', compact('user', 'setting'));
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $validatedData = $request->validated();

        $user = request()->user();

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

    public function updateEmail(UpdateEmailRequest $request)
    {
        $validatedData = $request->validated();

        $user = request()->user();

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

        $user = User::where('email', $validatedData['forgot-email'])->first();

        // Generate a temporary password (6 digits)
        $tempPassword = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update user with temporary password
        $user->update([
            'password' => Hash::make($tempPassword)
        ]);

        Mail::to($user->email)->send(new TemporaryPasswordMail($user, $tempPassword));

        return back()->with('success', 'A temporary password as been sent to your email! Please reset after logging.');
    }

    public function inventoryPreference(Request $request)
    {
        $validatedData = $request->validate([
            'currency' => 'required|string|max:1|in:$,€,£,¥,₦',
            'low_stock_threshold' => 'required|int|min:5|max:1000',
            'closing_time' => 'required|date_format:H:i,H:i:s',
        ], [
            'currency.in' => 'Please select a valid currency from the list.',
            'low_stock_threshold.min' => 'Low stock threshold must be at least 5',
            'low_stock_threshold.max' => 'Low stock threshold cannot exceed 1000',
            'closing_time.date_format' => 'Please provide a valid time in 24-hour format (HH:MM).',
        ]);

        
        $setting = Setting::find(1);

        if (!$setting)
        {
            $setting = Setting::create([
                'currency' => '₦',
                'low_stock_threshold' => 10,
            ]);
        }

        $setting->update($validatedData);

        return redirect()->back()->with('success', 'Stock preference update successfully!');
        
    }
}
