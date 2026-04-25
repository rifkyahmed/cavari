
<div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none">
    <!-- Toasts will be injected here -->
</div>

<script>
    window.showToast = function(message, type = 'success') {
        const container = document.getElementById('toast-container');
        
        // Create toast element
        const toast = document.createElement('div');
        // Glassmorphism Styles
        toast.className = `transform transition-all duration-500 translate-y-10 opacity-0 flex items-center w-full max-w-xs p-4 space-x-4 text-gray-800 bg-white/90 backdrop-blur-md border border-white/40 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] pointer-events-auto`;
        
        // Icon based on type
        let iconHtml = '';
        if (type === 'success') {
            iconHtml = `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-white bg-black rounded-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>`;
        } else if (type === 'error') {
            iconHtml = `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-white bg-red-500 rounded-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>`;
        } else if (type === 'info') {
             iconHtml = `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-white bg-gray-400 rounded-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>`;
        }


        toast.innerHTML = `
            ${iconHtml}
            <div class="ml-3 text-sm font-medium font-space-mono tracking-wide">${message}</div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-transparent text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-black/5 inline-flex items-center justify-center h-8 w-8 transition-colors" aria-label="Close" onclick="this.parentElement.remove()">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
        `;

        container.appendChild(toast);

        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-10', 'opacity-0');
        });

        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-10');
            setTimeout(() => {
                toast.remove();
            }, 500); // Wait for transition
        }, 3000);
    }

    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif

        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
        
        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast("{{ $error }}", 'error');
            @endforeach
        @endif
    });
</script>
