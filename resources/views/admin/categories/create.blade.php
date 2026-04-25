@extends('layouts.admin')

@section('header', 'Add Category')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Category Type</label>
            <select name="type" id="category_type" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                <option value="jewelry">Gem & Jewelry</option>
                <option value="loose_gem">Loose Gem</option>
            </select>
            <p class="text-xs text-gray-500 mt-1">Select the main classification.</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Parent Category (Optional)</label>
            <select name="parent_id" id="parent_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                <option value="">None (Top Level)</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" data-type="{{ $parent->type }}">
                        {{ $parent->name }} {{ $parent->parent ? '(under ' . $parent->parent->name . ')' : '' }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Select if this is a sub-category or sub-sub-category.</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
            <input type="text" name="name" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="flex justify-end">
            <a href="{{ route('admin.categories.index') }}" class="mr-4 text-gray-600 hover:underline flex items-center">Cancel</a>
            <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded hover:bg-indigo-700 transition">
                Create Category
            </button>
        </div>
    </form>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('category_type');
        const parentSelect = document.getElementById('parent_id');
        const allParentOptions = Array.from(parentSelect.options);

        let tomSelect = null;

        function filterParents() {
            const selectedType = typeSelect.value;
            
            // If TomSelect is already initialized, we need to handle it differently
            if (tomSelect) {
                // We'll hide/show items by re-adding them or just filtering the view?
                // Actually TomSelect handles filtering well. 
                // But we only want to show parents segments by type.
                
                // Simple way: clear and re-add valid ones
                tomSelect.clear();
                tomSelect.clearOptions();
                
                allParentOptions.forEach(opt => {
                    if (opt.value === "") return;
                    const dataType = opt.getAttribute('data-type');
                    if (dataType === selectedType) {
                        tomSelect.addOption({
                            value: opt.value,
                            text: opt.textContent.trim(),
                            type: dataType
                        });
                    }
                });
                tomSelect.refreshOptions(false);
            } else {
                // Initialize TomSelect for the first time
                tomSelect = new TomSelect('#parent_id', {
                    create: true,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    placeholder: 'Type to search parent category...',
                    allowEmptyOption: true,
                });
                filterParents(); // Run once to filter initially
            }
        }

        typeSelect.addEventListener('change', filterParents);
        filterParents();
    });
</script>
@endpush
@endsection
