@extends('layouts.admin')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold font-space-mono text-gray-800">Source Requests</h1>
</div>

<div class="glass-panel overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
             <thead class="bg-white/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">User/Email</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Details</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider font-space-mono">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($requests as $request)
                    <tr class="hover:bg-white/40 transition-colors cursor-pointer" onclick="window.location='{{ route('admin.source-requests.show', $request->id) }}'">
                        <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                            <div class="font-medium text-gray-900">{{ $request->name ?? ($request->user->name ?? 'Guest') }}</div>
                             <div class="text-xs text-gray-500">{{ $request->email ?? ($request->user->email ?? '') }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($request->product_details, 50) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-bold rounded-full 
                                {{ $request->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" onclick="event.stopPropagation()">
                            <a href="{{ route('admin.source-requests.show', $request->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold mr-4">View Details</a>
                            <form action="{{ route('admin.source-requests.update', $request->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="text-green-600 hover:text-green-900 font-bold mr-2">Mark Complete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">No source requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
     <div class="px-6 py-4 border-t border-gray-200">
        {{ $requests->links() }}
    </div>
</div>
@endsection
