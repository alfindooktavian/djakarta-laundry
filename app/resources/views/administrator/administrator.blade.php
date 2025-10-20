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
<div id="tambahUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6 relative">
        <button onclick="closeModal('tambahUserModal')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
            <iconify-icon icon="mdi:close" width="24" height="24"></iconify-icon>
        </button>
        <h2 class="text-xl font-semibold mb-4">Tambah User</h2>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Nama</label>
            <input id="inputName" type="text" name="name" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Nama" />
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Email</label>
            <input id="inputEmail" type="email" name="email" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Email" />
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Password</label>
            <input id="inputPassword" type="password" name="password" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Password" />
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Role</label>
            <select id="inputRole" name="role" class="border rounded px-3 py-2 w-full">
                <option value="superadmin">Superadmin</option>
                <option value="owner">Owner</option>
                <option value="karyawan">Karyawan</option>
            </select>
        </div>

        <div class="flex justify-end gap-2">
            <button onclick="closeModal('tambahUserModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Batal</button>
            <button onclick="handleCreateUser()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">Simpan</button>
        </div>
    </div>
</div>

<!-- Modal Detail / Edit User -->
<!-- Modal Detail / Edit User -->
<div id="detailUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6 relative">
        <button onclick="closeModal('detailUserModal')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
            <iconify-icon icon="mdi:close" width="24" height="24"></iconify-icon>
        </button>
        <h2 class="text-xl font-semibold mb-4">Detail User</h2>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Nama</label>
            <input id="detailName" type="text" name="name" class="border rounded px-3 py-2 w-full" />
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Email</label>
            <input id="detailEmail" type="email" name="email" class="border rounded px-3 py-2 w-full" />
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Role</label>
            <select id="detailRole" name="role" class="border rounded px-3 py-2 w-full">
                <option value="superadmin">Superadmin</option>
                <option value="owner">Owner</option>
                <option value="karyawan">Karyawan</option>
            </select>
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Status</label>
            <select id="detailStatus" name="status" class="border rounded px-3 py-2 w-full">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Password Baru (kosongkan jika tidak diubah)</label>
            <input id="detailPassword" type="password" name="password" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Password Baru" />
        </div>

        <div class="flex justify-end gap-2">
            <button onclick="closeModal('detailUserModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Tutup</button>
            <button onclick="handleUpdateUser()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">Simpan</button>
        </div>
    </div>
</div>


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
                        class="flex justify-center items-center rounded-lg border text-[#F5F5F5] font-inter text-[16px] leading-none"
                        style="background-color:#1E1E1E; border-color:#2C2C2C; width:64.5px; height:32px;"
                    >
                        Edit
                    </button>

                    <!-- Tombol Hapus -->
                    <button 
                        onclick="handleDeleteUser(${user.id})"
                        class="flex justify-center items-center rounded-lg border text-[#1E1E1E] font-inter text-[16px] leading-none"
                        style="background-color:#CDCDCD; border-color:#767676; width:64.5px; height:32px;"
                    >
                        Hapus
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
