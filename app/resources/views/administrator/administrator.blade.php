@extends('layouts.app')

@section('title', 'Administrator')

@section('content')
<div class="space-y-6">
    <x-title title="User" />
    <x-add-button onclick="openModal('tambahUserModal')">Tambah</x-add-button>

    <div class="bg-white shadow overflow-x-auto">
        <table class="min-w-full border-y border-gray-200 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">No</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Nama</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Email</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Role</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Status</th>
                    <th class="px-4 py-4 text-center text-sm font-semibold text-gray-700 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah User -->
{{-- Modal Tambah User --}}
<x-modal id="tambahUserModal" title="Tambah User">
    <div class="flex flex-col gap-2">
        <div>
            <label class="text-gray-700">Nama</label>
            <input id="inputName" type="text" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Nama" />
        </div>
        <div>
            <label class="text-gray-700">Email</label>
            <input id="inputEmail" type="email" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Email" />
        </div>
        <div>
            <label class="text-gray-700">Password</label>
            <input id="inputPassword" type="password" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Password" />
        </div>
        <div>
            <label class="text-gray-700">Role</label>
            <select id="inputRole" class="border rounded px-3 py-2 w-full">
                <option value="superadmin">Superadmin</option>
                <option value="owner">Owner</option>
                <option value="karyawan">Karyawan</option>
            </select>
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('tambahUserModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
        <button onclick="handleCreateUser()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">Simpan</button>
    </x-slot>
</x-modal>

<!-- Modal Detail / Edit User -->
<x-modal id="detailUserModal" title="Detail User">
    <div class="flex flex-col gap-2">
        <div>
            <label class="text-gray-700">Nama</label>
            <input id="detailName" type="text" class="border rounded px-3 py-2 w-full" />
        </div>
        <div>
            <label class="text-gray-700">Email</label>
            <input id="detailEmail" type="email" class="border rounded px-3 py-2 w-full" />
        </div>
        <div>
            <label class="text-gray-700">Role</label>
            <select id="detailRole" class="border rounded px-3 py-2 w-full">
                <option value="superadmin">Superadmin</option>
                <option value="owner">Owner</option>
                <option value="karyawan">Karyawan</option>
            </select>
        </div>
        <div>
            <label class="text-gray-700">Status</label>
            <select id="detailStatus" class="border rounded px-3 py-2 w-full">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>
        <div>
            <label class="text-gray-700">Password Baru (kosongkan jika tidak diubah)</label>
            <input id="detailPassword" type="password" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Password Baru" />
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('detailUserModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
        <button onclick="handleUpdateUser()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">Simpan</button>
    </x-slot>
</x-modal>


@vite('resources/js/api/users.js')

<script>
let currentEditUserId = null;

async function renderUsers() {
    const tbody = document.getElementById('userTableBody');
    console.log('Render users mulai...');

    const users = await fetchUsers();
    console.log('Data user dari API:', users);

    if (!users.length) {
        tbody.innerHTML = `<tr>
            <td colspan="5" class="text-center py-4 text-gray-500">Belum ada data administrator.</td>
        </tr>`;
        return;
    }

    tbody.innerHTML = users.map((user, index) => `
        <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-4 text-gray-700 border-b">${index + 1}</td>
            <td class="px-4 py-4 text-gray-700 border-b">${user.name}</td>
            <td class="px-4 py-4 text-gray-700 border-b">${user.email}</td>
            <td class="px-4 py-4 text-gray-700 border-b">${user.role}</td>
            <td class="px-4 py-4 text-gray-700 border-b">${user.status}</td>
            <td class="px-4 py-4 text-center border-b">
                <div class="flex justify-center items-center gap-4">
                    <!-- Tombol Detail / Edit -->
                    <button 
                        onclick="handleDetailUser(${user.id})"
                        class="flex justify-center items-center rounded-lg text-white w-8 h-8
               bg-gray-800 border border-gray-700 shadow-md hover:shadow-xl
               transform hover:scale-110 transition-all duration-300"
    >
        <iconify-icon class="transition-transform transform hover:rotate-12 hover:scale-125" 
                       icon="mdi:eye-outline" width="20" height="20" color="#FFFFFF">
        </iconify-icon>
                    </button>

                    <!-- Tombol Hapus -->
                    <button 
    onclick="handleDeleteUser(${user.id})"
    class="flex justify-center items-center rounded-lg text-white w-8 h-8
               bg-red-600 border border-red-800 shadow-md hover:shadow-xl
               transform hover:scale-110 transition-all duration-300"
    >
        <iconify-icon class="transition-transform transform hover:rotate-12 hover:scale-125" 
                       icon="mdi:delete-outline" width="20" height="20" color="#FFFFFF">
        </iconify-icon>
</button>
                </div>
            </td>
        </tr>
    `).join('');
}

async function handleCreateUser() {
    const data = {
        name: document.getElementById('inputName').value,
        email: document.getElementById('inputEmail').value,
        password: document.getElementById('inputPassword').value,
        role: document.getElementById('inputRole').value
    };

    const res = await createUser(data);
    console.log('Hasil create:', res);
    if(res){
        closeModal('tambahUserModal');
        renderUsers();
    }
}

async function handleDeleteUser(id) {
    if(confirm('Yakin ingin menghapus user ini?')){
        const res = await deleteUser(id);
        console.log('Hasil delete:', res);
        if(res){
            renderUsers();
        }
    }
}

async function handleDetailUser(id) {
    const user = await fetchUserById(id);
    if(!user) return alert('User tidak ditemukan');

    currentEditUserId = id;

    document.getElementById('detailName').value = user.name;
    document.getElementById('detailEmail').value = user.email;
    document.getElementById('detailRole').value = user.role;
    document.getElementById('detailStatus').value = user.status;


    openModal('detailUserModal');
}

async function handleUpdateUser() {
    if(!currentEditUserId) return;

    const data = {
        name: document.getElementById('detailName').value,
        email: document.getElementById('detailEmail').value,
        role: document.getElementById('detailRole').value,
        status: document.getElementById('detailStatus').value,
    };

    const newPassword = document.getElementById('detailPassword').value;
    if(newPassword) {
        data.password = newPassword; // hanya kirim jika ada password baru
    }

    const res = await updateUser(currentEditUserId, data);
    console.log('Hasil update:', res);

    if(res){
        closeModal('detailUserModal');
        renderUsers();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    renderUsers();
});
</script>
@endsection
