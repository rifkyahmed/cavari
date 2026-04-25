@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    
        <h2 class="font-space-mono text-2xl font-bold text-gray-800">Settings</h2>

    <!-- General Settings -->
    <div class="glass-panel p-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 font-space-mono">General Site Settings</h2>
            <p class="mt-1 text-sm text-gray-600 font-instrument">
                Update global store information and contact details.
            </p>
        </header>

        <form method="post" action="{{ route('admin.settings.general') }}" class="mt-6 space-y-6">
            @csrf
            @method('put')

            <div class="space-y-4 pb-6 border-b border-gray-200">
                <header>
                    <h3 class="text-sm font-medium text-gray-900 font-space-mono">Announcement Bar</h3>
                </header>
                <div class="flex items-start gap-4">
                     <div class="flex-1">
                        <label for="announcement_text" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Message</label>
                        <input id="announcement_text" name="announcement_text" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50" value="{{ $settings['announcement_text'] ?? '' }}" placeholder="Free Shipping on all orders over $500!" />
                    </div>
                     <div class="flex items-center h-full pt-6">
                        <label class="inline-flex items-center">
                            <input type="hidden" name="announcement_enabled" value="0">
                            <input type="checkbox" name="announcement_enabled" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ ($settings['announcement_enabled'] ?? '0') == '1' ? 'checked' : '' }}>
                            <span class="ml-2 font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Enabled</span>
                        </label>
                    </div>
                </div>
            </div>
                <!-- Store Info -->
                <div class="space-y-6">
                     <div>
                        <label for="site_name" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Store Name</label>
                        <input id="site_name" name="site_name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50" value="{{ $settings['site_name'] ?? config('app.name') }}" />
                    </div>
                     <div>
                        <label for="contact_phone" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Contact Phone</label>
                        <input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50" value="{{ $settings['contact_phone'] ?? '' }}" placeholder="+1 (555) 000-0000" />
                    </div>
                     <div>
                        <label for="contact_email" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Contact Email (Public)</label>
                        <input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50" value="{{ $settings['contact_email'] ?? '' }}" placeholder="hello@cavari.com" />
                    </div>
                        <div>
                            <label for="invoice_template" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Invoice Template</label>
                            <select id="invoice_template" name="invoice_template" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50">
                                <option value="modern" {{ ($settings['invoice_template'] ?? 'modern') === 'modern' ? 'selected' : '' }}>Modern</option>
                                <option value="default" {{ ($settings['invoice_template'] ?? 'modern') === 'default' ? 'selected' : '' }}>Default</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500 font-instrument">Choose which invoice Blade template to render.</p>
                        </div>
                </div>

                <!-- Address & Social -->
                <div class="space-y-6">
                    <div>
                        <label for="store_address" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Store Address</label>
                        <textarea id="store_address" name="store_address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50">{{ $settings['store_address'] ?? '' }}</textarea>
                    </div>
                     <div>
                        <label for="social_instagram" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Instagram URL</label>
                        <input id="social_instagram" name="social_instagram" type="url" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50" value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/..." />
                    </div>
                    <div>
                        <label for="social_facebook" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Facebook URL</label>
                        <input id="social_facebook" name="social_facebook" type="url" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50" value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/..." />
                    </div>
                </div>
            </div>
             
             <!-- Policy / Terms Links (Optional) -->
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-200">
                <div>
                     <label for="shipping_cost" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Flat Shipping Rate</label>
                     <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                          <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="shipping_cost" id="shipping_cost" class="block w-full rounded-md border-gray-300 pl-7 pr-12 focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50" placeholder="0.00" value="{{ $settings['shipping_cost'] ?? '0.00' }}" step="0.01">
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                          <span class="text-gray-500 sm:text-sm">USD</span>
                        </div>
                      </div>
                </div>
                 <div>
                     <label for="free_shipping_threshold" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Free Shipping Threshold</label>
                     <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                          <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="free_shipping_threshold" id="free_shipping_threshold" class="block w-full rounded-md border-gray-300 pl-7 pr-12 focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50" placeholder="0.00" value="{{ $settings['free_shipping_threshold'] ?? '' }}" step="0.01">
                      </div>
                </div>
             </div>


            <div class="flex items-center gap-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                    Save General Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Profile Information -->
    <div class="glass-panel p-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 font-space-mono">Profile Information</h2>
            <p class="mt-1 text-sm text-gray-600 font-instrument">
                Update your account's profile information and email address.
            </p>
        </header>

        <form method="post" action="{{ route('admin.settings.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('put')

            <div>
                <label for="name" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Name</label>
                <input id="name" name="name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                @error('name')
                    <p class="mt-2 text-sm text-red-600 space-y-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Email</label>
                <input id="email" name="email" type="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                @error('email')
                    <p class="mt-2 text-sm text-red-600 space-y-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                    Save Profile
                </button>
            </div>
        </form>
    </div>

    <!-- Update Password -->
    <div class="glass-panel p-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 font-space-mono">Update Password</h2>
            <p class="mt-1 text-sm text-gray-600 font-instrument">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form method="post" action="{{ route('admin.settings.password') }}" class="mt-6 space-y-6">
            @csrf
            @method('put')

            <div>
                <label for="current_password" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Current Password</label>
                <input id="current_password" name="current_password" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50" autocomplete="current-password" />
                @error('current_password')
                    <p class="mt-2 text-sm text-red-600 space-y-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">New Password</label>
                <input id="password" name="password" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50" autocomplete="new-password" />
                @error('password')
                    <p class="mt-2 text-sm text-red-600 space-y-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block font-space-mono text-xs font-bold uppercase tracking-widest text-gray-700">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 bg-white/50" autocomplete="new-password" />
                @error('password_confirmation')
                    <p class="mt-2 text-sm text-red-600 space-y-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                    Save Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
