@extends('layouts.app')

@section('title', 'Services')

@section('content')
<div class="space-y-6">
    <x-title title="Layanan" />
    <x-add-button onclick="openModal('tambahServiceModal')">Tambah</x-add-button>

    <div class="bg-white shadow overflow-x-auto">
        <table class="min-w-full border-y border-gray-200 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">No</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Nama</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Harga</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Tipe</th>
                    <th class="px-4 py-4 text-center text-sm font-semibold text-gray-700 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody id="serviceTableBody">
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div id="paginationContainer" class="flex justify-end gap-2 mt-4"></div>


<!-- Modal Tambah Service -->
<x-modal id="tambahServiceModal" title="Tambah Service">
    <div class="flex flex-col gap-2">
        <div>
            <label class="text-gray-700">Nama</label>
            <input id="inputName" type="text" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Nama Service" />
        </div>
        <div>
            <label class="text-gray-700">Harga</label>
            <input id="inputPrice" type="number" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Harga" />
        </div>
        <div>
            <label class="text-gray-700">Tipe</label>
            <select id="inputType" class="border rounded px-3 py-2 w-full">
                <option value="kg">Kilogram (kg)</option>
                <option value="item">Item (pcs)</option>
            </select>
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('tambahServiceModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
        <button onclick="handleCreateService()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">Simpan</button>
    </x-slot>
</x-modal>

<!-- Modal Detail / Edit Service -->
<x-modal id="detailServiceModal" title="Edit Service">
    <div class="flex flex-col gap-2">
        <div>
            <label class="text-gray-700">Nama</label>
            <input id="detailName" type="text" class="border rounded px-3 py-2 w-full" />
        </div>
        <div>
            <label class="text-gray-700">Harga</label>
            <input id="detailPrice" type="number" class="border rounded px-3 py-2 w-full" />
        </div>
        <div>
            <label class="text-gray-700">Tipe</label>
            <select id="detailType" class="border rounded px-3 py-2 w-full">
                <option value="kg">Kilogram (kg)</option>
                <option value="item">Item (pcs)</option>
            </select>
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('detailServiceModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
        <button onclick="handleUpdateService()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">Simpan</button>
    </x-slot>
</x-modal>


@vite('resources/js/api/services.js')

<script>
let currentEditServiceId = null;
let lastPage = 1;

function renderPagination(currentPage, lastPage) {
    const container = document.getElementById('paginationContainer');
    let html = '';

    // Previous
    html += `<button onclick="gotoPage(${currentPage-1})" class="px-3 py-1 border rounded hover:bg-gray-200 ${currentPage==1?'opacity-50 cursor-not-allowed':''}">&lt;</button>`;

    let start = Math.max(currentPage-1, 1);
    let end = Math.min(start+2, lastPage);
    start = Math.max(end-2, 1);

    for(let i=start; i<=end; i++){
        html += `<button onclick="gotoPage(${i})" class="px-3 py-1 border rounded ${i==currentPage?'bg-gray-800 text-white':'hover:bg-gray-200'}">${i}</button>`;
    }

    // Next
    html += `<button onclick="gotoPage(${currentPage+1})" class="px-3 py-1 border rounded hover:bg-gray-200 ${currentPage==lastPage?'opacity-50 cursor-not-allowed':''}">&gt;</button>`;

    container.innerHTML = html;
}

function gotoPage(page) {
    if(page < 1 || page > lastPage) return;
    renderServices(page);
}

async function renderServices(page = 1) {
    const tbody = document.getElementById('serviceTableBody');
    const { services, current_page, last_page } = await fetchServices(page);

    lastPage = last_page;

    if (!services.length) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-gray-500">Belum ada data service.</td></tr>`;
        return;
    }

    tbody.innerHTML = services.map((service, index) => `
        <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-4 text-gray-700 border-b">${(current_page-1)*5 + index + 1}</td>
            <td class="px-4 py-4 text-gray-700 border-b">${service.name}</td>
            <td class="px-4 py-4 text-gray-700 border-b">Rp ${service.price.toLocaleString()}</td>
            <td class="px-4 py-4 text-gray-700 border-b">${service.type}</td>
            <td class="px-4 py-4 text-center border-b">
                <div class="flex justify-center items-center gap-4">
                    <button onclick="handleDetailService(${service.id})" class="flex justify-center items-center rounded-lg text-white w-8 h-8 bg-gray-800 border border-gray-700 shadow-md hover:shadow-xl transform hover:scale-110 transition-all duration-300">
                        <iconify-icon icon="mdi:eye-outline" width="20" height="20" color="#FFFFFF"></iconify-icon></button>
                    <button onclick="handleDeleteService(${service.id})" class="flex justify-center items-center rounded-lg text-white w-8 h-8 bg-red-600 border border-red-800 shadow-md hover:shadow-xl transform hover:scale-110 transition-all duration-300">
                        <iconify-icon icon="mdi:delete-outline" width="20" height="20" color="#FFFFFF"></iconify-icon></button>
                </div>
            </td>
        </tr>
    `).join('');

    renderPagination(current_page, last_page);
}

document.addEventListener('DOMContentLoaded', () => renderServices());

async function handleCreateService() {
    const data = {
        name: document.getElementById('inputName').value,
        price: parseFloat(document.getElementById('inputPrice').value),
        type: document.getElementById('inputType').value
    };

    const res = await createService(data);
    console.log('Hasil create service:', res);
    if(res){
        closeModal('tambahServiceModal');
        renderServices();
    }
}

async function handleDeleteService(id) {
    if(confirm('Yakin ingin menghapus service ini?')){
        const res = await deleteService(id);
        console.log('Hasil delete service:', res);
        if(res){
            renderServices();
        }
    }
}

async function handleDetailService(id) {
    const service = await fetchServiceById(id);
    if(!service) return alert('Service tidak ditemukan');

    currentEditServiceId = id;

    document.getElementById('detailName').value = service.name;
    document.getElementById('detailPrice').value = service.price;
    document.getElementById('detailType').value = service.type;

    openModal('detailServiceModal');
}

async function handleUpdateService() {
    if(!currentEditServiceId) return;

    const data = {
        name: document.getElementById('detailName').value,
        price: parseFloat(document.getElementById('detailPrice').value),
        type: document.getElementById('detailType').value
    };

    const res = await updateService(currentEditServiceId, data);
    console.log('Hasil update service:', res);

    if(res){
        closeModal('detailServiceModal');
        renderServices();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    renderServices();
});
</script>
@endsection
