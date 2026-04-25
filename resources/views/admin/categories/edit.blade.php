@extends('layouts.admin')

@section('header', 'Edit Category')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Category Type</label>
            <input type="text" value="{{ $category->type == 'loose_gem' ? 'Loose Gem' : 'Gem & Jewelry' }}" class="w-full border-gray-100 rounded-md bg-gray-50 text-gray-500 cursor-not-allowed" readonly>
            <input type="hidden" name="type" id="category_type" value="{{ $category->type }}">
            <p class="text-[10px] text-gray-400 mt-1">Classification cannot be changed after creation.</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Parent Category (Optional)</label>
            <select name="parent_id" id="parent_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                <option value="">None (Top Level)</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" data-type="{{ $parent->type }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }} {{ $parent->parent ? '(under ' . $parent->parent->name . ')' : '' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
            <input type="text" name="name" value="{{ $category->name }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="flex justify-end">
            <a href="{{ route('admin.categories.index') }}" class="mr-4 text-gray-600 hover:underline flex items-center">Cancel</a>
            <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded hover:bg-indigo-700 transition">
                Update Category
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
        const initialValue = parentSelect.value;

        function filterParents() {
            const selectedType = typeSelect.value;
            
            if (tomSelect) {
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
                tomSelect = new TomSelect('#parent_id', {
                    create: true,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    },
                    placeholder: 'Type to search parent category...',
                    allowEmptyOption: true,
                });
                filterParents(); 
                if (initialValue) {
                    tomSelect.setValue(initialValue);
                }
            }
        }

        typeSelect.addEventListener('change', filterParents);
        filterParents();
    });
</script>
@endpush
@endsection
