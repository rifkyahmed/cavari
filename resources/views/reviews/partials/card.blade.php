<div class="review-card">

    {{-- Stars --}}
    <div class="flex gap-1 mb-5">
        @for($i = 1; $i <= 5; $i++)
            <svg class="w-3 h-3 {{ $i <= $review->rating ? 'fill-black' : 'fill-black/12' }}"
                 viewBox="0 0 24 24">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
        @endfor
    </div>

    {{-- Quote --}}
    <p class="font-instrument text-base text-gray-700 leading-relaxed mb-8 break-words">
        "{{ $review->comment }}"
    </p>

    {{-- Author --}}
    <div class="border-t border-black/8 pt-5">
        <h4 class="font-gloock text-lg text-black leading-tight">{{ $review->author_name }}</h4>
        <span class="font-space-mono text-[9px] font-bold uppercase tracking-[0.2em] text-black/35 mt-1 block">
            {{ $review->location ?? 'Verified Client' }}
        </span>
    </div>

</div>
