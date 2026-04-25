
<div id="auth-modal" class="fixed inset-0 z-[100] hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    
    <!-- Backdrop -->
    <div id="auth-modal-backdrop" class="fixed inset-0 bg-black/20 backdrop-blur-sm transition-opacity opacity-0 duration-300"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            
            <!-- Modal Panel -->
            <div id="auth-modal-panel" class="relative transform overflow-hidden transition-all opacity-0 translate-y-4 duration-300 max-w-md w-full"
                 style="background: rgba(255, 255, 255, 0.45); border-radius: 24px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1); backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px); border: 1px solid rgba(255, 255, 255, 0.4);">
                
                <!-- Close Button -->
                <button type="button" onclick="closeAuthModal()" class="absolute top-4 right-4 text-gray-800 hover:text-black transition-colors z-20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <div class="p-8 md:p-10 relative z-10">
                    
                    <!-- Header -->
                    <div class="mb-8 text-center">
                        <span class="font-space-mono text-[10px] font-bold uppercase tracking-[0.2em] text-gray-800 block mb-2" id="modal-subtitle">
                            Welcome Back
                        </span>
                        <h2 class="font-gloock text-3xl md:text-4xl text-black leading-none" id="modal-title">
                            Access Your <span class="italic text-gray-600">Atelier.</span>
                        </h2>
                    </div>

                    <!-- LOGIN FORM -->
                    <div id="login-form-container" class="transition-all duration-300">
                        <div id="login-errors" class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-600 text-xs font-instrument rounded-lg"></div>
                        
                        <form id="login-form" method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="intended_url" value="{{ session('intended_url') }}">
                            
                            <div class="group">
                                <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black/80 block mb-2 group-focus-within:text-black transition-colors">Email Address</label>
                                <input type="email" name="email" required autofocus class="w-full bg-transparent border-b border-black/30 focus:border-black py-2 font-instrument text-lg outline-none transition-colors">
                            </div>

                            <div class="group">
                                <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black/80 block mb-2 group-focus-within:text-black transition-colors">Password</label>
                                <input type="password" name="password" required class="w-full bg-transparent border-b border-black/30 focus:border-black py-2 font-instrument text-lg outline-none transition-colors">
                            </div>

                            <div class="flex items-center justify-between mt-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="remember" value="1" class="w-4 h-4 text-black border-gray-300 rounded focus:ring-black">
                                    <span class="ml-2 font-instrument text-sm text-gray-800">Remember me</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <button type="button" onclick="toggleAuthMode('forgot-password')" class="font-instrument text-sm text-gray-800 hover:text-black hover:underline underline-offset-4">Forgot?</button>
                                @endif
                            </div>

                            <button type="submit" class="w-full py-3 bg-black text-white font-space-mono text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition shadow-lg rounded-sm mt-4">
                                Sign In
                            </button>
                        </form>

                        <div class="mt-8 text-center">
                            <p class="font-instrument text-sm text-gray-800">
                                Not a member yet? 
                                <button onclick="toggleAuthMode('register')" class="text-black font-bold hover:underline underline-offset-4">Create Account</button>
                            </p>
                        </div>
                    </div>

                    <!-- FORGOT PASSWORD FORM -->
                    <div id="forgot-form-container" class="hidden transition-all duration-300">
                        <div id="forgot-errors" class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-600 text-xs font-instrument rounded-lg"></div>
                        <div id="forgot-message" class="hidden mb-4 p-3 bg-green-50 border border-green-200 text-green-700 text-xs font-instrument rounded-lg"></div>
                        
                        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                            @csrf
                            
                            <p class="font-instrument text-sm text-gray-600 mb-4 px-1">
                                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
                            </p>

                            <div class="group">
                                <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black/80 block mb-2 group-focus-within:text-black transition-colors">Email Address</label>
                                <input type="email" name="email" required autofocus class="w-full bg-transparent border-b border-black/30 focus:border-black py-2 font-instrument text-lg outline-none transition-colors">
                            </div>

                            <button type="submit" class="w-full py-3 bg-black text-white font-space-mono text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition shadow-lg rounded-sm mt-4">
                                Email Password Reset Link
                            </button>
                        </form>

                        <div class="mt-8 text-center has-tooltip">
                            <button onclick="toggleAuthMode('login')" class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black/60 hover:text-black transition-colors flex items-center justify-center gap-2 mx-auto">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                <span>Back to Login</span>
                            </button>
                        </div>
                    </div>

                    <!-- VERIFY CODE FORM -->
                    <div id="verify-code-form-container" class="hidden transition-all duration-300">
                        <div id="verify-errors" class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-600 text-xs font-instrument rounded-lg"></div>
                        
                        <form id="verify-code-form" method="POST" action="{{ route('password.verify-code') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="email" id="verify-email">
                            
                            <p class="font-instrument text-sm text-gray-600 mb-4 px-1">
                                Please enter the verification code sent to your email.
                            </p>

                            <div class="group">
                                <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black/80 block mb-2 group-focus-within:text-black transition-colors">Verification Code</label>
                                <input type="text" name="code" required autofocus class="w-full bg-transparent border-b border-black/30 focus:border-black py-2 font-instrument text-lg outline-none transition-colors tracking-widest text-center" placeholder="000000">
                            </div>

                            <button type="submit" class="w-full py-3 bg-black text-white font-space-mono text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition shadow-lg rounded-sm mt-4">
                                Verify Code
                            </button>
                        </form>
                         <div class="mt-8 text-center">
                            <button onclick="toggleAuthMode('forgot-password')" class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black/60 hover:text-black transition-colors">Resend Code</button>
                        </div>
                    </div>

                    <!-- RESET PASSWORD FORM -->
                    <div id="reset-password-form-container" class="hidden transition-all duration-300">
                        <div id="reset-errors" class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-600 text-xs font-instrument rounded-lg"></div>
                        
                        <form id="reset-password-form" method="POST" action="{{ route('password.reset-with-code') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="email" id="reset-email">
                            <input type="hidden" name="code" id="reset-code">

                            <div class="group">
                                <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black/80 block mb-2 group-focus-within:text-black transition-colors">New Password</label>
                                <input type="password" name="password" required class="w-full bg-transparent border-b border-black/30 focus:border-black py-2 font-instrument text-lg outline-none transition-colors">
                            </div>

                            <div class="group">
                                <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black/80 block mb-2 group-focus-within:text-black transition-colors">Confirm Password</label>
                                <input type="password" name="password_confirmation" required class="w-full bg-transparent border-b border-black/30 focus:border-black py-2 font-instrument text-lg outline-none transition-colors">
                            </div>

                            <button type="submit" class="w-full py-3 bg-black text-white font-space-mono text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition shadow-lg rounded-sm mt-4">
                                Reset Password
                            </button>
                        </form>
                    </div>

                    <!-- REGISTER FORM (Hidden by default) -->
                    <div id="register-form-container" class="hidden transition-all duration-300">
                        <div id="register-errors" class="hidden mb-4 p-3 bg-red-50 border border-red-200 text-red-600 text-xs font-instrument rounded-lg"></div>
                        
                        <form method="POST" action="{{ route('register') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="intended_url" value="{{ session('intended_url') }}">
                            
                            <div class="group">
                                <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black/80 block mb-2 group-focus-within:text-black transition-colors">Full Name</label>
                                <input type="text" name="name" required autofocus class="w-full bg-transparent border-b border-black/30 focus:border-black py-2 font-instrument text-lg outline-none transition-colors">
                            </div>

                            <div class="group">
                                <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black/80 block mb-2 group-focus-within:text-black transition-colors">Email Address</label>
                                <input type="email" name="email" required class="w-full bg-transparent border-b border-black/30 focus:border-black py-2 font-instrument text-lg outline-none transition-colors">
                            </div>

                            <div class="group">
                                <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black/80 block mb-2 group-focus-within:text-black transition-colors">Birthday</label>
                                <input type="date" name="birthday" class="w-full bg-transparent border-b border-black/30 focus:border-black py-2 font-instrument text-lg outline-none transition-colors">
                            </div>

                            <div class="group">
                                <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black/80 block mb-2 group-focus-within:text-black transition-colors">Password</label>
                                <input type="password" name="password" required class="w-full bg-transparent border-b border-black/30 focus:border-black py-2 font-instrument text-lg outline-none transition-colors">
                            </div>

                            <div class="group">
                                <label class="font-space-mono text-[10px] font-bold uppercase tracking-widest text-black/80 block mb-2 group-focus-within:text-black transition-colors">Confirm Password</label>
                                <input type="password" name="password_confirmation" required class="w-full bg-transparent border-b border-black/30 focus:border-black py-2 font-instrument text-lg outline-none transition-colors">
                            </div>

                            <button type="submit" class="w-full py-3 bg-black text-white font-space-mono text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition shadow-lg rounded-sm mt-4">
                                Join The Atelier
                            </button>
                        </form>

                        <div class="mt-8 text-center">
                            <p class="font-instrument text-sm text-gray-800">
                                Already have an account? 
                                <button onclick="toggleAuthMode('login')" class="text-black font-bold hover:underline underline-offset-4">Sign In</button>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openAuthModal() {
        const modal = document.getElementById('auth-modal');
        const backdrop = document.getElementById('auth-modal-backdrop');
        const panel = document.getElementById('auth-modal-panel');
        
        modal.classList.remove('hidden');
        // Trigger reflow
        void modal.offsetWidth;
        
        backdrop.classList.remove('opacity-0');
        panel.classList.remove('opacity-0', 'translate-y-4');
    }

    function closeAuthModal() {
        const modal = document.getElementById('auth-modal');
        const backdrop = document.getElementById('auth-modal-backdrop');
        const panel = document.getElementById('auth-modal-panel');
        
        backdrop.classList.add('opacity-0');
        panel.classList.add('opacity-0', 'translate-y-4');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            // Reset to login mode
            toggleAuthMode('login');
            // Clear errors
            document.getElementById('login-errors').classList.add('hidden');
            document.getElementById('login-errors').innerHTML = '';
        }, 300);
    }

    function toggleAuthMode(mode) {
        const loginContainer = document.getElementById('login-form-container');
        const registerContainer = document.getElementById('register-form-container');
        const forgotContainer = document.getElementById('forgot-form-container');
        const verifyContainer = document.getElementById('verify-code-form-container');
        const resetContainer = document.getElementById('reset-password-form-container');
        
        const modalTitle = document.getElementById('modal-title');
        const modalSubtitle = document.getElementById('modal-subtitle');

        // Hide all
        loginContainer.classList.add('hidden');
        registerContainer.classList.add('hidden');
        if (forgotContainer) forgotContainer.classList.add('hidden');
        if (verifyContainer) verifyContainer.classList.add('hidden');
        if (resetContainer) resetContainer.classList.add('hidden');

        if (mode === 'register') {
            registerContainer.classList.remove('hidden');
            modalSubtitle.textContent = 'Become a Member';
            modalTitle.innerHTML = 'Join the <span class="italic text-gray-600">Legacy.</span>';
        } else if (mode === 'forgot-password') {
            if (forgotContainer) forgotContainer.classList.remove('hidden');
            modalSubtitle.textContent = 'Account Recovery';
            modalTitle.innerHTML = 'Reset Your <span class="italic text-gray-600">Password.</span>';
        } else if (mode === 'verify-code') {
            if (verifyContainer) verifyContainer.classList.remove('hidden');
            modalSubtitle.textContent = 'Verification';
            modalTitle.innerHTML = 'Enter <span class="italic text-gray-600">Code.</span>';
        } else if (mode === 'reset-password') {
            if (resetContainer) resetContainer.classList.remove('hidden');
            modalSubtitle.textContent = 'New Password';
            modalTitle.innerHTML = 'Secure Your <span class="italic text-gray-600">Account.</span>';
        } else {
            loginContainer.classList.remove('hidden');
            modalSubtitle.textContent = 'Welcome Back';
            modalTitle.innerHTML = 'Access Your <span class="italic text-gray-600">Atelier.</span>';
        }
    }

    // AJAX Auth Handler
    document.addEventListener('DOMContentLoaded', () => {
        const loginForm = document.getElementById('login-form');
        const registerForm = document.querySelector('#register-form-container form');
        const forgotForm = document.querySelector('#forgot-form-container form');
        const verifyForm = document.getElementById('verify-code-form');
        const resetForm = document.getElementById('reset-password-form');
        
        const loginErrorContainer = document.getElementById('login-errors');
        const registerErrorContainer = document.getElementById('register-errors');
        const forgotErrorContainer = document.getElementById('forgot-errors');
        const verifyErrorContainer = document.getElementById('verify-errors');
        const resetErrorContainer = document.getElementById('reset-errors');

        function getSecureUrl(element) {
            try {
                // If it's a form element with an action
                let rawAction = typeof element === 'string' ? element : element.getAttribute('action');
                let url = new URL(rawAction, window.location.origin);
                
                // Force the fetch URL to align exactly with the browser's active domain
                url.host = window.location.host;
                url.protocol = window.location.protocol;
                return url.toString();
            } catch(e) {
                return element;
            }
        }

        // Login Handler
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                handleAuthSubmit(this, getSecureUrl(this), loginErrorContainer, 'login');
            });
        }

        // Register Handler
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                handleAuthSubmit(this, getSecureUrl(this), registerErrorContainer, 'register');
            });
        }

        // Forgot Password Handler
        if (forgotForm) {
            forgotForm.addEventListener('submit', function(e) {
                e.preventDefault();
                handleAuthSubmit(this, getSecureUrl(this), forgotErrorContainer, 'forgot-password');
            });
        }

        // Verify Code Handler
        if (verifyForm) {
            verifyForm.addEventListener('submit', function(e) {
                e.preventDefault();
                handleAuthSubmit(this, getSecureUrl(this), verifyErrorContainer, 'verify-code');
            });
        }
        
        // Reset Password Handler
        if (resetForm) {
            resetForm.addEventListener('submit', function(e) {
                e.preventDefault();
                handleAuthSubmit(this, getSecureUrl(this), resetErrorContainer, 'reset-password');
            });
        }

        function handleAuthSubmit(form, url, errorContainer, type) {
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = 'Processing...';
            submitBtn.disabled = true;
            
            // Clear errors
            if(errorContainer) {
                errorContainer.classList.add('hidden');
                errorContainer.innerHTML = '';
            }
            // Clear success message for forgot form
            if (type === 'forgot-password') {
                const msg = document.getElementById('forgot-message');
                if (msg) msg.classList.add('hidden');
            }

            // Ensure we stay on the current page if no intended URL is provided
            if (formData.has('intended_url') && !formData.get('intended_url')) {
                formData.set('intended_url', window.location.href);
            }

            fetch(url, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                if (status >= 200 && status < 300) {
                    // Success
                    if (type === 'forgot-password') {
                        if (typeof showToast === 'function') showToast(body.message, 'success');
                        
                        // Switch to Verify Code
                        const email = formData.get('email');
                        document.getElementById('verify-email').value = email;
                        
                        setTimeout(() => toggleAuthMode('verify-code'), 500);
                        
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        return;
                    }
                    
                    if (type === 'verify-code') {
                        if (typeof showToast === 'function') showToast(body.message, 'success');
                        
                        // Switch to Reset Password
                        const email = formData.get('email');
                        const code = formData.get('code');
                        document.getElementById('reset-email').value = email;
                        document.getElementById('reset-code').value = code;
                        
                        setTimeout(() => toggleAuthMode('reset-password'), 500);
                        
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        return;
                    }
                    
                    if (type === 'reset-password') {
                        if (typeof showToast === 'function') showToast(body.message, 'success');
                        setTimeout(() => {
                            if (body.redirect) {
                                window.location.href = body.redirect;
                            } else {
                                window.location.reload();
                            }
                        }, 1000);
                        return;
                    }

                    if (type === 'register') {
                        if (typeof showToast === 'function') showToast('Account created successfully!', 'success');
                    } else {
                        if (typeof showToast === 'function') showToast('Login successful!', 'success');
                    }

                    setTimeout(() => {
                        if (body.redirect) {
                            window.location.href = body.redirect;
                        } else {
                            window.location.reload();
                        }
                    }, 1000);
                } else {
                    // Error
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    // Message for Toast
                    let toastMsg = body.message || 'An error occurred.';
                    
                    if (status === 422 && body.errors) {
                        // 1. Show in container (Priority)
                        if (errorContainer) {
                             let errorsHtml = '<ul class="list-disc list-inside">';
                             for (const [field, messages] of Object.entries(body.errors)) {
                                 errorsHtml += `<li>${messages[0]}</li>`;
                             }
                             errorsHtml += '</ul>';
                             errorContainer.innerHTML = errorsHtml;
                             errorContainer.classList.remove('hidden');
                        }

                        // 2. Show Toast (Secondary)
                        if (typeof showToast === 'function') {
                            Object.keys(body.errors).forEach(field => {
                                const msg = body.errors[field][0];
                                showToast(msg, 'error');
                            });
                        }
                    } else {
                        // General Error
                        if (errorContainer) {
                            errorContainer.innerHTML = toastMsg;
                            errorContainer.classList.remove('hidden');
                        }
                        if (typeof showToast === 'function') {
                            showToast(toastMsg, 'error');
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Auth error:', error);
                if (typeof showToast === 'function') showToast('Network error. Please try again.', 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }
    });

    // Close on backdrop click
    document.getElementById('auth-modal-backdrop').addEventListener('click', closeAuthModal);
</script>
