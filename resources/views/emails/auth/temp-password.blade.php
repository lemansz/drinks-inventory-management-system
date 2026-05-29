<x-mail::message>
# Hello, {{ $user->name }}

A temporary password has been generated for your account. Please use the credentials below to log in and update your password in the settings immediately.

**Temporary Password:** `{{ $tempPassword }}`

<x-mail::button :url="route('login')">
Login to Your Account
</x-mail::button>

If you did not request this, please contact the administrator.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
