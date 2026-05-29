<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>mcheck</title>
    @livewireStyles
</head>
@vite('resources/css/app.css')

<body class="bg-[var(--body-bg)] pt-6">
    @if (session('success'))
        <div class="absolute right-0 top-0">
            <x-alert :message="session('success')" :show='true'/>
        </div>
    @endif

    <div class="text-center">
        <h1 class="text-[var(--main-color)] text-7xl shadow-2xs"><span class="text-green-400 text-9xl ">m</span>check</h1>
        <p class=" text-[var(--sec)] mt-2 text-2xl">Best way to save records.</p>
    </div>

    <div class="h-64 flex justify-center items-center">
        <x-forms.form action="{{ route('login') }}" method="POST" class="border p-2 rounded border-gray-500/50">
            <div class="mb-4">
                <x-forms.input class="ml-10" type="email" name="email" label="Email" placeholder="admin@gmail.com" value="{{ old('email') }}" />
            </div>
            <div class="mb-4">
                <x-forms.input type="password" name="password" placeholder="Enter 6-digit password" label="Password" maxlength="6" />
            </div>
            
            <button type="submit" id="loginBtn" class="border rounded px-4 py-1 bg-black text-white active:bg-black/70 hover:bg-black/80 disabled:bg-black/50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2">
                <span id="loginText">Login</span>
                <svg id="spinner" class="hidden w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
            
            <div class="text-center mt-4">
                <button type="button" onclick="document.getElementById('forgotPasswordModal').showModal()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Forgot Password?
                </button>
            </div>
        </x-forms.form>
    </div>

    <!-- Forgot Password Modal -->
    <dialog id="forgotPasswordModal" class="backdrop:bg-black/50 rounded-lg shadow-2xl self-center mx-auto">
        <div class="w-96 p-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Forgot Password</h2>
                <button type="button" onclick="document.getElementById('forgotPasswordModal').close()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Info Alert -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm text-blue-800">Enter your email address and we'll send you a temporary password.</p>
            </div>

            <!-- Form -->
            <form action="{{ route('password.forgot') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Email Input -->
                <div>
                    <label for="forgot-email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="forgot-email" id="forgot-email" placeholder="Enter your email" class="w-full px-4 py-2 border @error('forgot-email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" value="{{ old('forgot-email') }}">
                    @error('forgot-email')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <button type="button" onclick="document.getElementById('forgotPasswordModal').close()" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg transition-colors">
                        Send Password
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    @if ($errors->has('forgot-email'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('forgotPasswordModal').showModal();
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form[action="{{ route("login") }}"]');
            const loginBtn = document.getElementById('loginBtn');
            const spinner = document.getElementById('spinner');
            const loginText = document.getElementById('loginText');

            loginForm.addEventListener('submit', function() {
                loginBtn.disabled = true;
                spinner.classList.remove('hidden');
                loginText.textContent = 'Logging in...';
            });
        });
    </script>
    @livewireScripts
</body>
</html>
