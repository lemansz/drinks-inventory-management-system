<x-layout>
    @if (session('success'))
        <div class="absolute right-0 top-0">
            <x-alert :message="session('success')" :show='true'/>
        </div>
    @endif
    <div class="ml-0 p-8 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800">Settings</h1>
            <p class="text-gray-600 mt-2">Manage your account and application preferences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Account Information Card -->
            <div class="lg:col-span-2 space-y-6">
                <!-- User Profile Section -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Profile Information</h2>
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                            <span class="text-emerald-700 font-bold text-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Name Field -->
                        <div class="border-b border-gray-200 pb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <p class="text-lg text-gray-900 font-medium">{{ $user->surname }} {{ $user->first_name }}</p>
                        </div>

                        <!-- Email Field -->
                        <div class="border-b border-gray-200 pb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <p class="text-lg text-gray-900 font-medium">{{ $user->email }}</p>
                                </div>
                                <button type="button" onclick="document.getElementById('emailModal').showModal()" class="ml-4 inline-flex items-center px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Change
                                </button>
                            </div>
                        </div>

                        <!-- Change Password Section -->
                        <div class="pt-4">
                            <button type="button" onclick="document.getElementById('passwordModal').showModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Change Password
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Application Settings Section -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Application Settings</h2>
                    
                    <x-forms.form action="{{ route('settings.inventory.preference') }}" method="POST" class="space-y-6">
                        <!-- Currency Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                                <div class="relative">
                                    <select id="currency" name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent appearance-none bg-white">
                                        <option value="₦" {{ $setting && $setting->currency === '₦' ? 'selected' : '' }}>Nigerian Naira (₦)</option>
                                        <option value="$" {{ $setting && $setting->currency === '$' ? 'selected' : '' }}>US Dollar ($)</option>
                                        <option value="€" {{ $setting && $setting->currency === '€' ? 'selected' : '' }}>Euro (€)</option>
                                        <option value="£" {{ $setting && $setting->currency === '£' ? 'selected' : '' }}>British Pound (£)</option>
                                        <option value="¥" {{ $setting && $setting->currency === '¥' ? 'selected' : '' }}>Chinese Yuan (¥)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414l-2.293 2.293a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>

                                    @error('currency')
                                        <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Current: <span class="font-semibold text-gray-700">{{ $setting?->currency ?? '₦' }}</span></p>
                            </div>

                            <!-- Low Stock Threshold -->
                            <div>
                                <label for="low-stock" class="block text-sm font-medium text-gray-700 mb-2">Low Stock Threshold</label>
                                <div class="relative">
                                    <input type="number" id="low-stock" name="low_stock_threshold" value="{{ $setting?->low_stock_threshold ?? 10 }}" min="1" max="1000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                                </div>
                                @error('low_stock_threshold')
                                    <div class="text-red-500">{{ $message }}</div>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500">Alert when stock falls below: <span class="font-semibold text-gray-700">{{ $setting?->low_stock_threshold ?? 10 }} units</span></p>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </x-forms.form>
                </div>
            </div>

            <!-- Sidebar Stats -->
            <div class="lg:col-span-1">
                <!-- Quick Info Card -->
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Quick Info</h3>
                    
                    <div class="space-y-4">
                        <!-- Currency Display -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-blue-900">Currency</span>
                                <span class="text-2xl font-bold text-blue-700">{{ $setting?->currency ?? '₦' }}</span>
                            </div>
                        </div>

                        <!-- Low Stock Display -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-orange-900">Low Stock Alert</span>
                                <span class="text-2xl font-bold text-orange-700">{{ $setting?->low_stock_threshold ?? 10 }}</span>
                            </div>
                            <p class="text-xs text-orange-600 mt-2">units threshold</p>
                        </div>

                        <!-- Recordin -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                            <div class="space-y-1">
                                <span class="text-sm font-medium text-gray-600 block">Recording start date</span>
                                <span class="text-lg font-semibold text-gray-800">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Email Modal -->
    <dialog id="emailModal" class="backdrop:bg-black/50 rounded-lg shadow-2xl self-center mx-auto">
        <div class="w-96 p-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Change Email</h2>
                <button type="button" onclick="document.getElementById('emailModal').close()" class="text-gray-400 hover:text-gray-600 transition-colors">
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
                <p class="text-sm text-blue-800">The entered email shall receive monthly reports.</p>
            </div>

            <!-- Form -->
            <x-forms.form class="space-y-5" action="{{ route('email.update') }}" method="POST">
                <!-- Current Email (Read-only) -->
                <div>
                    <label for="current-email" class="block text-sm font-medium text-gray-700 mb-2">Current Email</label>
                    <input type="email" id="current-email" value="{{ $user->email }}" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                </div>

                <!-- New Email -->
                <div>
                    <label for="new-email" class="block text-sm font-medium text-gray-700 mb-2">New Email Address</label>
                    <input type="email" name="new-email" id="new-email" placeholder="Enter your new email" class="w-full px-4 py-2 border @error('new-email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" value="{{ old('new-email') }}">
                    @error('new-email')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm New Email -->
                <div>
                    <label for="confirm-email" class="block text-sm font-medium text-gray-700 mb-2">Confirm Email</label>
                    <input type="email" name="confirm-email" id="confirm-email" placeholder="Re-enter your new email" class="w-full px-4 py-2 border @error('confirm-email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" value="{{ old('confirm-email') }}">
                    @error('confirm-email')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Verification -->
                <div>
                    <label for="email-password" class="block text-sm font-medium text-gray-700 mb-2">Enter Your Password</label>
                    <div class="relative">
                        <input type="password" name="email-password" id="email-password" placeholder="Enter your password to confirm" class="w-full px-4 py-2 pr-10 border @error('email-password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" value="{{ old('email-password') }}">
                        <button type="button" onclick="togglePasswordVisibility('email-password', 'email-password-toggle')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="email-password-toggle" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('email-password')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">We need your password to confirm this change</p>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeEmailModal()" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 text-white bg-emerald-600 hover:bg-emerald-700 font-medium rounded-lg transition-colors">
                        Update Email
                    </button>
                </div>
            </x-forms.form>
        </div>
    </dialog>

    @if ($errors->has('new-email') || $errors->has('confirm-email') || $errors->has('email-password'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('emailModal').showModal();
            });
        </script>
    @endif

    <!-- Change Password Modal -->
    <dialog id="passwordModal" class="backdrop:bg-black/50 rounded-lg shadow-2xl self-center mx-auto">
        <div class="w-96 p-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Change Password</h2>
                <button type="button" onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <x-forms.form class="space-y-5" action="{{ route('password.update') }}" method="POST">
                <!-- Current Password -->
                <div>
                    <label for="current-password" class="block text-sm font-medium text-gray-700 mb-2">Current password</label>
                    <div class="relative">
                        <input type="password" name="current-password" maxlength="6" id="current-password" placeholder="Enter your current password" class="w-full px-4 py-2 pr-10 border @error('current-password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" value="{{ old('current-password') }}">
                        <button type="button" onclick="togglePasswordVisibility('current-password', 'current-password-toggle')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="current-password-toggle" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('current-password')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="new-password" class="block text-sm font-medium text-gray-700 mb-2">New password</label>
                    <div class="relative">
                        <input type="password" name="new-password" maxlength="6" id="new-password" placeholder="Enter your new password" class="w-full px-4 py-2 pr-10 border @error('new-password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" value="{{ old('new-password') }}">
                        <button type="button" onclick="togglePasswordVisibility('new-password', 'new-password-toggle')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="new-password-toggle" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('new-password')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-2">Confirm password</label>
                    <div class="relative">
                        <input type="password" name="confirm-password" maxlength="6" id="confirm-password" placeholder="Re-enter your new password" class="w-full px-4 py-2 pr-10 border @error('confirm-password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" value="{{ old('confirm-password') }}">
                        <button type="button" onclick="togglePasswordVisibility('confirm-password', 'confirm-password-toggle')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg id="confirm-password-toggle" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @error('confirm-password')
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
                    <button type="button" onclick="closePasswordModal()" class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg transition-colors">
                        Update Password
                    </button>
                </div>
            </x-forms.form>
        </div>
    </dialog>

    @if ($errors->has('current-password') || $errors->has('new-password') || $errors->has('confirm-password'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('passwordModal').showModal();
            });
        </script>
    @endif

    <script>
        function closePasswordModal() {
            document.getElementById('passwordModal').close();
            // Redirect to clear errors from session
            window.location.href = '{{ route("settings.create") }}';
        }

        function closeEmailModal() {
            document.getElementById('emailModal').close();
            // Redirect to clear errors from session
            window.location.href = '{{ route("settings.create") }}';
        }

        function togglePasswordVisibility(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            
            if (field.type === 'password') {
                field.type = 'text';
                // Eye icon
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            } else {
                field.type = 'password';
                // Eye with diagonal line icon
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path><line stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="2" y1="2" x2="22" y2="22"></line>';
            }
        }
    </script>
</x-layout>