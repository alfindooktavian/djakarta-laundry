@props([
    'id' => 'modal',
    'title' => '',
])

<div id="{{ $id }}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-xl p-6 relative">
        <!-- Close button -->
        <button 
            type="button" 
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
            onclick="closeModal('{{ $id }}')"
        >
            &times;
        </button>

        <!-- Title -->
        @if($title)
            <h2 class="text-xl font-semibold mb-4">{{ $title }}</h2>
        @endif

        <!-- Content -->
        <div class="modal-content flex flex-col gap-4">
            {{ $slot }}
        </div>

        <!-- Button Group -->
        <div class="flex justify-end gap-2 mt-4">
            <button onclick="closeModal('{{ $id }}')" class="px-4 py-2 border rounded bg-gray-100 hover:bg-gray-200">Batal</button>
            <button class="px-4 py-2 rounded bg-black text-white hover:bg-gray-900">Simpan</button>
        </div>
    </div>
</div>
