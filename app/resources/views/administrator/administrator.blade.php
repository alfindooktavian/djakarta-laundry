@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')


   <!-- Button buka modal -->
<!-- Navigation Container -->
<div class="absolute left-[336px] right-[80px] top-[78px] h-10 flex items-center justify-end">
    <!-- Button Tambah -->
    <button 
        onclick="openModal('editUserModal')" 
        class="flex items-center justify-center gap-2 w-[94px] h-10 bg-black text-white rounded-md text-base font-medium hover:bg-gray-800 transition-colors"
    >
        Tambah
    </button>
</div>


    <!-- Modal Overlay -->
    <div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <!-- Modal Box -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6 relative">
            
            <!-- Close Button -->
            <button onclick="closeModal('editUserModal')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
                <iconify-icon icon="mdi:close" width="24" height="24"></iconify-icon>
            </button>

            <h2 class="text-xl font-semibold mb-4">Edit User</h2>

            <!-- Nama -->
            <div class="flex flex-col gap-1 mb-4">
                <label class="text-gray-700">Nama</label>
                <input type="text" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Nama" />
            </div>

            <!-- Email -->
            <div class="flex flex-col gap-1 mb-4">
                <label class="text-gray-700">Email</label>
                <input type="email" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Email" />
            </div>

            <!-- Role -->
            <div class="flex flex-col gap-1 mb-4">
                <label class="text-gray-700">Role</label>
                <select class="border rounded px-3 py-2 w-full">
                    <option>Owner</option>
                    <option>Superadmin</option>
                    <option>Karyawan</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-2">
                <button onclick="closeModal('editUserModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Batal</button>
                <button class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">Simpan</button>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if(modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if(modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>
</body>
</html>
