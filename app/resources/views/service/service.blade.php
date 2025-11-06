@extends('layouts.app')

@section('title', 'Layanan')

@section('content')
<div class="container mx-auto py-6 space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-2xl font-semibold text-gray-800">Manajemen Layanan</h3>
            <p class="text-gray-500 text-sm">Kelola daftar layanan, harga, dan tipe laundry.</p>
        </div>

        <button onclick="openModal('tambahServiceModal')"
            class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
            <iconify-icon icon="mdi:plus" width="18" height="18"></iconify-icon>
            Tambah Layanan
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama</th>
                    <th class="px-4 py-3 text-left font-semibold">Harga</th>
                    <th class="px-4 py-3 text-left font-semibold">Tipe</th>
                    <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody id="serviceTableBody">
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
<x-modal id="tambahServiceModal" title="Tambah Layanan">
    <div class="flex flex-col gap-3">
        <div>
            <label class="text-gray-700 font-medium">Nama</label>
            <input id="inputName" type="text" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400" placeholder="Masukkan Nama Layanan" />
        </div>
        <div>
            <label class="text-gray-700 font-medium">Harga</label>
            <input id="inputPrice" type="number" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400" placeholder="Masukkan Harga" />
        </div>
        <div>
            <label class="text-gray-700 font-medium">Tipe</label>
            <select id="inputType" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400">
                <option value="kg">Kilogram (kg)</option>
                <option value="item">Item (pcs)</option>
            </select>
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('tambahServiceModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Batal</button>
        <button onclick="handleCreateService()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Simpan</button>
    </x-slot>
</x-modal>

<!-- Modal Edit -->
<x-modal id="detailServiceModal" title="Edit Layanan">
    <div class="flex flex-col gap-3">
        <div>
            <label class="text-gray-700 font-medium">Nama</label>
            <input id="detailName" type="text" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400" />
        </div>
        <div>
            <label class="text-gray-700 font-medium">Harga</label>
            <input id="detailPrice" type="number" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400" />
        </div>
        <div>
            <label class="text-gray-700 font-medium">Tipe</label>
            <select id="detailType" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400">
                <option value="kg">Kilogram (kg)</option>
                <option value="item">Item (pcs)</option>
            </select>
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('detailServiceModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">Tutup</button>
        <button onclick="handleUpdateService()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Simpan</button>
    </x-slot>
</x-modal>

@vite('resources/js/api/services.js')

<script>
let currentEditServiceId = null;
let lastPage = 1;

// Badge warna tipe
function getTypeBadge(type) {
    switch (type.toLowerCase()) {
        case 'kg': return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Kilogram</span>';
        case 'item': return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Item</span>';
        default: return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">-</span>';
    }
}

// Pagination
function renderPagination(currentPage, lastPage) {
    const container = document.getElementById('paginationContainer');
    let html = '';
    html += `<button onclick="gotoPage(${currentPage - 1})" class="px-3 py-1 border rounded hover:bg-gray-100 ${currentPage==1?'opacity-50 cursor-not-allowed':''}">&lt;</button>`;
    let start = Math.max(currentPage - 1, 1);
    let end = Math.min(start + 2, lastPage);
    start = Math.max(end - 2, 1);
    for (let i = start; i <= end; i++) {
        html += `<button onclick="gotoPage(${i})" class="px-3 py-1 border rounded ${i==currentPage?'bg-blue-600 text-white':'hover:bg-gray-100'}">${i}</button>`;
    }
    html += `<button onclick="gotoPage(${currentPage + 1})" class="px-3 py-1 border rounded hover:bg-gray-100 ${currentPage==lastPage?'opacity-50 cursor-not-allowed':''}">&gt;</button>`;
    container.innerHTML = html;
}
function gotoPage(page){ if(page<1||page>lastPage)return; renderServices(page); }

// Render Table
async function renderServices(page = 1) {
    const tbody = document.getElementById('serviceTableBody');
    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-6 text-gray-500">Memuat data...</td></tr>`;

    const { services, current_page, last_page } = await fetchServices(page);
    lastPage = last_page;

    if (!services.length) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-6 text-gray-500">Belum ada data layanan.</td></tr>`;
        return;
    }

    tbody.innerHTML = services.map((service, index) => `
        <tr class="hover:bg-blue-50 transition">
            <td class="px-4 py-3 border-b border-gray-200">${(current_page - 1) * 5 + index + 1}</td>
            <td class="px-4 py-3 border-b border-gray-200">${service.name}</td>
            <td class="px-4 py-3 border-b border-gray-200 font-semibold">Rp ${Number(service.price).toLocaleString('id-ID')}</td>
            <td class="px-4 py-3 border-b border-gray-200">${getTypeBadge(service.type)}</td>
            <td class="px-4 py-3 border-b border-gray-200 text-center">
                <div class="flex justify-center items-center gap-3">
                    <button onclick="handleDetailService(${service.id})"
                        class="flex items-center justify-center w-8 h-8 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">
                        <iconify-icon icon="mdi:eye-outline" width="18" height="18"></iconify-icon>
                    </button>
                    <button onclick="handleDeleteService(${service.id})"
                        class="flex items-center justify-center w-8 h-8 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <iconify-icon icon="mdi:delete-outline" width="18" height="18"></iconify-icon>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    renderPagination(current_page, last_page);
}

// CRUD
async function handleCreateService() {
    const data = {
        name: document.getElementById('inputName').value.trim(),
        price: parseFloat(document.getElementById('inputPrice').value),
        type: document.getElementById('inputType').value
    };
    const res = await createService(data);
    if (res) {
        closeModal('tambahServiceModal');
        renderServices();
    }
}

async function handleDeleteService(id) {
    if (confirm('Yakin ingin menghapus layanan ini?')) {
        const res = await deleteService(id);
        if (res) renderServices();
    }
}

async function handleDetailService(id) {
    const service = await fetchServiceById(id);
    if (!service) return alert('Layanan tidak ditemukan');
    currentEditServiceId = id;
    document.getElementById('detailName').value = service.name;
    document.getElementById('detailPrice').value = service.price;
    document.getElementById('detailType').value = service.type;
    openModal('detailServiceModal');
}

async function handleUpdateService() {
    if (!currentEditServiceId) return;
    const data = {
        name: document.getElementById('detailName').value.trim(),
        price: parseFloat(document.getElementById('detailPrice').value),
        type: document.getElementById('detailType').value
    };
    const res = await updateService(currentEditServiceId, data);
    if (res) {
        closeModal('detailServiceModal');
        renderServices();
    }
}

document.addEventListener('DOMContentLoaded', () => renderServices());
</script>
@endsection
