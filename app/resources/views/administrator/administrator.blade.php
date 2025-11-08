@extends('layouts.app')

@section('title', 'Administrator')

@section('content')
<div class="container mx-auto py-6 space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-2xl font-semibold text-gray-800">Manajemen Administrator</h3>
            <p class="text-gray-500 text-sm">Kelola data pengguna, role, dan status akun sistem.</p>
        </div>

        <button onclick="openModal('tambahUserModal')"
            class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
            <iconify-icon icon="mdi:plus" width="18" height="18"></iconify-icon>
            Tambah User
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama</th>
                    <th class="px-4 py-3 text-left font-semibold">Email</th>
                    <th class="px-4 py-3 text-left font-semibold">Role</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="paginationContainer" class="flex justify-end gap-2 mt-4"></div>

</div>

<!-- Modal Tambah User -->
<x-modal id="tambahUserModal" title="Tambah User">
    <div class="flex flex-col gap-3">
        <div>
            <label class="text-gray-700 font-medium">Nama</label>
            <input id="inputName" type="text" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400" placeholder="Masukkan Nama">
        </div>
        <div>
            <label class="text-gray-700 font-medium">Email</label>
            <input id="inputEmail" type="email" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400" placeholder="Masukkan Email">
        </div>
        <div>
            <label class="text-gray-700 font-medium">Password</label>
            <input id="inputPassword" type="password" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400" placeholder="Masukkan Password">
        </div>
        <div>
            <label class="text-gray-700 font-medium">Role</label>
            <select id="inputRole" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400">
                <option value="superadmin">Superadmin</option>
                <option value="owner">Owner</option>
                <option value="karyawan">Karyawan</option>
            </select>
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('tambahUserModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Batal</button>
        <button onclick="handleCreateUser()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Simpan</button>
    </x-slot>
</x-modal>

<!-- Modal Detail User -->
<x-modal id="detailUserModal" title="Detail User">
    <div class="flex flex-col gap-3">
        <div>
            <label class="text-gray-700 font-medium">Nama</label>
            <input id="detailName" type="text" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="text-gray-700 font-medium">Email</label>
            <input id="detailEmail" type="email" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="text-gray-700 font-medium">Role</label>
            <select id="detailRole" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400">
                <option value="superadmin">Superadmin</option>
                <option value="owner">Owner</option>
                <option value="karyawan">Karyawan</option>
            </select>
        </div>
        <div>
            <label class="text-gray-700 font-medium">Status</label>
            <select id="detailStatus" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>
        <div>
            <label class="text-gray-700 font-medium">Password Baru (opsional)</label>
            <input id="detailPassword" type="password" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400" placeholder="Kosongkan jika tidak diubah">
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('detailUserModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Tutup</button>
        <button onclick="handleUpdateUser()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Simpan</button>
    </x-slot>
</x-modal>

@vite('resources/js/api/users.js')

<script>
let currentEditUserId = null;
let lastPage = 1;

// Utility: Badge warna
function getRoleBadge(role) {
    switch (role.toLowerCase()) {
        case 'superadmin': return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Superadmin</span>';
        case 'owner': return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Owner</span>';
        case 'karyawan': return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Karyawan</span>';
        default: return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">Unknown</span>';
    }
}

function getStatusBadge(status) {
    switch (status.toLowerCase()) {
        case 'aktif': return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>';
        case 'nonaktif': return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Nonaktif</span>';
        default: return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">Tidak diketahui</span>';
    }
}

// Pagination Renderer
function renderPagination(currentPage, lastPage) {
    const container = document.getElementById('paginationContainer');
    let html = '';

    html += `<button onclick="gotoPage(${currentPage-1})" class="px-3 py-1 border rounded hover:bg-gray-100 ${currentPage==1?'opacity-50 cursor-not-allowed':''}">&lt;</button>`;

    let start = Math.max(currentPage - 1, 1);
    let end = Math.min(start + 2, lastPage);
    start = Math.max(end - 2, 1);

    for (let i = start; i <= end; i++) {
        html += `<button onclick="gotoPage(${i})" class="px-3 py-1 border rounded ${i==currentPage?'bg-blue-600 text-white':'hover:bg-gray-100'}">${i}</button>`;
    }

    html += `<button onclick="gotoPage(${currentPage+1})" class="px-3 py-1 border rounded hover:bg-gray-100 ${currentPage==lastPage?'opacity-50 cursor-not-allowed':''}">&gt;</button>`;

    container.innerHTML = html;
}

function gotoPage(page) {
    if (page < 1 || page > lastPage) return;
    renderUsers(page);
}

// Render User Table
async function renderUsers(page = 1) {
    const tbody = document.getElementById('userTableBody');
    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-6 text-gray-500">Memuat data...</td></tr>`;

    const { users, current_page, last_page } = await fetchUsers(page);
    lastPage = last_page;

    if (!users.length) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-6 text-gray-500">Belum ada data administrator.</td></tr>`;
        return;
    }

    tbody.innerHTML = users.map((user, index) => `
        <tr class="hover:bg-blue-50 transition">
            <td class="px-4 py-3 border-b border-gray-200">${(current_page - 1) * 5 + index + 1}</td>
            <td class="px-4 py-3 border-b border-gray-200">${user.name}</td>
            <td class="px-4 py-3 border-b border-gray-200">${user.email}</td>
            <td class="px-4 py-3 border-b border-gray-200">${getRoleBadge(user.role)}</td>
            <td class="px-4 py-3 border-b border-gray-200">${getStatusBadge(user.status)}</td>
            <td class="px-4 py-3 border-b border-gray-200 text-center">
                <div class="flex justify-center items-center gap-3">
                    <button onclick="handleDetailUser(${user.id})"
                        class="flex items-center justify-center w-8 h-8 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">
                        <iconify-icon icon="mdi:eye-outline" width="18" height="18"></iconify-icon>
                    </button>
                    <button onclick="handleDeleteUser(${user.id})"
                        class="flex items-center justify-center w-8 h-8 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <iconify-icon icon="mdi:delete-outline" width="18" height="18"></iconify-icon>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    renderPagination(current_page, last_page);
}

// CRUD Handlers
async function handleCreateUser() {
    const data = {
        name: document.getElementById('inputName').value,
        email: document.getElementById('inputEmail').value,
        password: document.getElementById('inputPassword').value,
        role: document.getElementById('inputRole').value,
    };

    const res = await createUser(data);
    if (res) {
        closeModal('tambahUserModal');
        renderUsers();
    }
}

async function handleDeleteUser(id) {
    const res = await deleteUser(id);
    if (res && !res.cancelled) renderUsers();
}


async function handleDetailUser(id) {
    const user = await fetchUserById(id);
    if (!user) return alert('User tidak ditemukan');

    currentEditUserId = id;
    document.getElementById('detailName').value = user.name;
    document.getElementById('detailEmail').value = user.email;
    document.getElementById('detailRole').value = user.role;
    document.getElementById('detailStatus').value = user.status;

    openModal('detailUserModal');
}

async function handleUpdateUser() {
    if (!currentEditUserId) return;

    const data = {
        name: document.getElementById('detailName').value,
        email: document.getElementById('detailEmail').value,
        role: document.getElementById('detailRole').value,
        status: document.getElementById('detailStatus').value,
    };

    const newPassword = document.getElementById('detailPassword').value;
    if (newPassword) data.password = newPassword;

    const res = await updateUser(currentEditUserId, data);
    if (res) {
        closeModal('detailUserModal');
        renderUsers();
    }
}

document.addEventListener('DOMContentLoaded', renderUsers);
</script>
@endsection
