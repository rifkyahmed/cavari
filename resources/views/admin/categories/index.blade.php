@extends('layouts.admin')

@section('header', 'Categories')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold font-space-mono text-gray-800">Product Categories</h1>
    <div class="flex gap-4">
        <form action="{{ route('admin.categories.destroyAll') }}" method="POST" onsubmit="return confirm('WARNING: This will delete ALL categories. Proceed?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 text-white font-bold py-2 px-4 rounded hover:bg-red-600 transition text-sm uppercase font-space-mono">
                Delete All Categories
            </button>
        </form>
        <a href="{{ route('admin.categories.create') }}" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 transition text-sm uppercase font-space-mono">
            Add New Category
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-space-mono">Product Count</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($categories as $category)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($category->parent)
                            <span class="text-[10px] text-gray-400">
                                @if($category->parent->parent)
                                    {{ $category->parent->parent->name }} &gt; 
                                @endif
                                {{ $category->parent->name }}
                            </span>
                        @else
                            <span class="text-[10px] text-gray-300 italic">None</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($category->type == 'loose_gem')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Loose Gem</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Gem & Jewelry</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono text-[10px]">{{ $category->slug }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->products()->count() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium font-space-mono uppercase">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
