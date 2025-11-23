@props([
    'id' => 'modal',
    'title' => '',
    'maxWidth' => 'max-w-lg',
])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full {{ $maxWidth }} mx-4 p-6 relative flex flex-col transition-all duration-300 transform scale-95">
        
        <!-- Tombol Tutup -->
        <button 
            type="button" 
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition"
            onclick="closeModal('{{ $id }}')"
        >
            <iconify-icon icon="mdi:close" width="26" height="26"></iconify-icon>
        </button>

        <!-- Header -->
        @if($title || isset($header))
            <div class="mb-4 border-b border-gray-200 pb-3">
                @if($title)
                    <h2 class="text-xl font-semibold text-gray-800">{{ $title }}</h2>
                @endif
                {{ $header ?? '' }}
            </div>
        @endif

        <!-- Body -->
        <div class="modal-body flex-1 overflow-y-auto text-gray-700">
            {{ $slot }}
        </div>

        <!-- Footer -->
        @if(isset($footer))
            <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end gap-3">
                {{ $footer }}
            </div>
        @else
            <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end gap-3">
                <button 
                    onclick="closeModal('{{ $id }}')" 
                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition"
                >
                    Batal
                </button>
                <button 
                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition"
                >
                    Simpan
                </button>
            </div>
        @endif
    </div>
</div>
