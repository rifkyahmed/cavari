@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold font-space-mono text-gray-800">Website Reviews</h1>
    <a href="{{ route('admin.website-reviews.create') }}" class="px-6 py-2 bg-black text-white font-bold rounded-lg shadow hover:bg-gray-800 transition-colors font-space-mono text-sm uppercase flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add Fake Review
    </a>
</div>

<div class="glass-panel overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Author</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Comment</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Status</th>
                     <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Type</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($reviews as $review)
                    <tr class="hover:bg-white/40 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $review->author_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-yellow-500 font-bold">
                            {{ str_repeat('★', $review->rating) }}<span class="text-gray-300">{{ str_repeat('★', 5 - $review->rating) }}</span>
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($review->comment, 50) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                             <span class="px-2 py-1 text-xs font-bold rounded-full {{ $review->is_approved ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $review->is_approved ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap">
                             <span class="px-2 py-1 text-xs font-bold rounded-full {{ $review->is_fake ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $review->is_fake ? 'Fake' : 'Real' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.website-reviews.edit', $review->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 font-bold">Edit</a>
                            
                            <form action="{{ route('admin.website-reviews.destroy', $review->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">No reviews found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
     <div class="px-6 py-4 border-t border-gray-200">
        {{ $reviews->links() }}
    </div>
</div>
@endsection
