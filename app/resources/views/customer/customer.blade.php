@extends('layouts.app')

@section('title', 'Pelanggan')

@section('content')
<div class="container mx-auto py-6 space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-2xl font-semibold text-gray-800">Manajemen Pelanggan</h3>
            <p class="text-gray-500 text-sm">Kelola daftar pelanggan dan informasi kontak mereka.</p>
        </div>

        <button onclick="openModal('tambahCustomerModal')"
            class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
            <iconify-icon icon="mdi:plus" width="18" height="18"></iconify-icon>
            Tambah Pelanggan
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama</th>
                    <th class="px-4 py-3 text-left font-semibold">Telepon</th>
                    <th class="px-4 py-3 text-left font-semibold">Alamat</th>
                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody id="customerTableBody">
                <tr>
                    <td colspan="5" class="text-center py-6 text-gray-500">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="paginationContainer" class="flex justify-end gap-2 mt-4"></div>
</div>

<!-- Modal Tambah -->
<x-modal id="tambahCustomerModal" title="Tambah Pelanggan">
    <div class="flex flex-col gap-3">
        <div>
            <label class="text-gray-700 font-medium">Nama</label>
            <input id="inputName" type="text"
                class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400"
                placeholder="Masukkan Nama Pelanggan" />
        </div>
        <div>
            <label class="text-gray-700 font-medium">Telepon</label>
            <input id="inputPhone" type="text"
                class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400"
                placeholder="Masukkan Nomor Telepon" />
        </div>
        <div>
            <label class="text-gray-700 font-medium">Alamat</label>
            <textarea id="inputAddress"
                class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400"
                placeholder="Masukkan Alamat"></textarea>
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('tambahCustomerModal')"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
            Batal
        </button>
        <button onclick="handleCreateCustomer()"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Simpan
        </button>
    </x-slot>
</x-modal>

<!-- Modal Edit -->
<x-modal id="detailCustomerModal" title="Edit Pelanggan">
    <div class="flex flex-col gap-3">
        <div>
            <label class="text-gray-700 font-medium">Nama</label>
            <input id="detailName" type="text"
                class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400" />
        </div>
        <div>
            <label class="text-gray-700 font-medium">Telepon</label>
            <input id="detailPhone" type="text"
                class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400" />
        </div>
        <div>
            <label class="text-gray-700 font-medium">Alamat</label>
            <textarea id="detailAddress"
                class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400"></textarea>
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('detailCustomerModal')"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
            Tutup
        </button>
        <button onclick="handleUpdateCustomer()"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Simpan
        </button>
    </x-slot>
</x-modal>

@vite('resources/js/api/customers.js')

<script>
let currentEditCustomerId = null;
let lastPage = 1;

// Pagination
function renderPagination(currentPage, lastPage) {
    const container = document.getElementById('paginationContainer');
    let html = '';

    html += `<button onclick="gotoPage(${currentPage - 1})" class="px-3 py-1 border rounded hover:bg-gray-100 ${currentPage == 1 ? 'opacity-50 cursor-not-allowed' : ''}">&lt;</button>`;

    let start = Math.max(currentPage - 1, 1);
    let end = Math.min(start + 2, lastPage);
    start = Math.max(end - 2, 1);

    for (let i = start; i <= end; i++) {
        html += `<button onclick="gotoPage(${i})" class="px-3 py-1 border rounded ${i == currentPage ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'}">${i}</button>`;
    }

    html += `<button onclick="gotoPage(${currentPage + 1})" class="px-3 py-1 border rounded hover:bg-gray-100 ${currentPage == lastPage ? 'opacity-50 cursor-not-allowed' : ''}">&gt;</button>`;

    container.innerHTML = html;
}

function gotoPage(page) {
    if (page < 1 || page > lastPage) return;
    renderCustomers(page);
}

// Render Customers
async function renderCustomers(page = 1) {
    const tbody = document.getElementById('customerTableBody');
    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-6 text-gray-500">Memuat data...</td></tr>`;

    const { customers, current_page, last_page } = await fetchCustomers(page, false);
    lastPage = last_page;

    if (!customers.length) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-6 text-gray-500">Belum ada data pelanggan.</td></tr>`;
        return;
    }

    tbody.innerHTML = customers.map((c, index) => `
        <tr class="hover:bg-blue-50 transition">
            <td class="px-4 py-3 border-b border-gray-200">${(current_page - 1) * 5 + index + 1}</td>
            <td class="px-4 py-3 border-b border-gray-200 font-medium text-gray-800">${c.name}</td>
            <td class="px-4 py-3 border-b border-gray-200">${c.phone || '-'}</td>
            <td class="px-4 py-3 border-b border-gray-200">${c.address || '-'}</td>
            <td class="px-4 py-3 border-b border-gray-200 text-center">
                <div class="flex justify-center items-center gap-3">
                    <button onclick="handleDetailCustomer(${c.id})"
                        class="flex justify-center items-center w-8 h-8 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">
                        <iconify-icon icon="mdi:eye-outline" width="18" height="18"></iconify-icon>
                    </button>
                    <button onclick="handleDeleteCustomer(${c.id})"
                        class="flex justify-center items-center w-8 h-8 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <iconify-icon icon="mdi:delete-outline" width="18" height="18"></iconify-icon>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    renderPagination(current_page, last_page);
}

// CRUD
async function handleCreateCustomer() {
    const data = {
        name: document.getElementById('inputName').value.trim(),
        phone: document.getElementById('inputPhone').value.trim(),
        address: document.getElementById('inputAddress').value.trim(),
    };
    const res = await createCustomer(data);
    if (res) {
        closeModal('tambahCustomerModal');
        renderCustomers();
    }
}

async function handleDeleteCustomer(id) {
    const res = await deleteCustomer(id);
    if (res && !res.cancelled) renderCustomers();
}

async function handleDetailCustomer(id) {
    const customer = await fetchCustomerById(id);
    if (!customer) return alert('Pelanggan tidak ditemukan');
    currentEditCustomerId = id;

    document.getElementById('detailName').value = customer.name;
    document.getElementById('detailPhone').value = customer.phone || '';
    document.getElementById('detailAddress').value = customer.address || '';

    openModal('detailCustomerModal');
}

async function handleUpdateCustomer() {
    if (!currentEditCustomerId) return;

    const data = {
        name: document.getElementById('detailName').value.trim(),
        phone: document.getElementById('detailPhone').value.trim(),
        address: document.getElementById('detailAddress').value.trim(),
    };
    const res = await updateCustomer(currentEditCustomerId, data);
    if (res) {
        closeModal('detailCustomerModal');
        renderCustomers();
    }
}

document.addEventListener('DOMContentLoaded', () => renderCustomers());
</script>
@endsection
