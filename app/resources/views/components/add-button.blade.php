<div class="flex justify-end">
    <button 
        onclick="{{ $onclick ?? '' }}" 
        class="flex items-center justify-center gap-2 px-6 py-2 bg-black text-white rounded-md text-base font-medium hover:bg-gray-800 transition-colors"
    >
        {{ $slot ?: 'Tambah' }}
    </button>
</div>
