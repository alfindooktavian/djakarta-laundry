@props([
    'id' => 'modal',
    'title' => '',
])

<div id="{{ $id }}" class="fixed inset-0 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-[600px] h-[450px] p-4 relative flex flex-col">
        
        <!-- Close button -->
        <button 
            type="button" 
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
            onclick="closeModal('{{ $id }}')"
        >
            <iconify-icon icon="mdi:close" width="28" height="28"></iconify-icon>
        </button>

        <!-- Header -->
        @if($title || isset($header))
            <div class="mb-2 border-b border-gray-300 pb-5">
                @if($title)
                    <h2 class="text-lg font-semibold">{{ $title }}</h2>
                @endif
                {{ $header ?? '' }}
            </div>
        @endif

        <!-- Body -->
        <div class="modal-body flex-1 overflow-y-auto">
            {{ $slot }}
        </div>

        <!-- Footer -->
        @if(isset($footer))
            <div class="mt-2 border-t border-gray-300 pt-5 flex justify-end gap-2">
                {{ $footer }}
            </div>
        @else
            <div class="mt-2 border-t border-gray-300 pt-2 flex justify-end gap-2">
                <button onclick="closeModal('{{ $id }}')" class="px-4 py-2 border rounded bg-gray-100 hover:bg-gray-200">Batal</button>
                <button class="px-4 py-2 rounded bg-black text-white hover:bg-gray-900">Simpan</button>
            </div>
        @endif
    </div>
</div>
