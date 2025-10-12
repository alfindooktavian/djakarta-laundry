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

<!-- Modal Tambah Service -->
<div id="tambahServiceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6 relative">
        <button onclick="closeModal('tambahServiceModal')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
            <iconify-icon icon="mdi:close" width="24" height="24"></iconify-icon>
        </button>
        <h2 class="text-xl font-semibold mb-4">Tambah Service</h2>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Nama</label>
            <input id="inputName" type="text" name="name" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Nama Service" />
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Harga</label>
            <input id="inputPrice" type="number" name="price" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Harga" />
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Tipe</label>
            <select id="inputType" name="type" class="border rounded px-3 py-2 w-full">
                <option value="kg">Kilogram (kg)</option>
                <option value="item">Item (pcs)</option>
            </select>
        </div>

        <div class="flex justify-end gap-2">
            <button onclick="closeModal('tambahServiceModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Batal</button>
            <button onclick="handleCreateService()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">Simpan</button>
        </div>
    </div>
</div>

<!-- Modal Detail / Edit Service -->
<div id="detailServiceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6 relative">
        <button onclick="closeModal('detailServiceModal')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
            <iconify-icon icon="mdi:close" width="24" height="24"></iconify-icon>
        </button>
        <h2 class="text-xl font-semibold mb-4">Edit Service</h2>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Nama</label>
            <input id="detailName" type="text" class="border rounded px-3 py-2 w-full" />
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Harga</label>
            <input id="detailPrice" type="number" class="border rounded px-3 py-2 w-full" />
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Tipe</label>
            <select id="detailType" class="border rounded px-3 py-2 w-full">
                <option value="kg">Kilogram (kg)</option>
                <option value="item">Item (pcs)</option>
            </select>
        </div>

        <div class="flex justify-end gap-2">
            <button onclick="closeModal('detailServiceModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Tutup</button>
            <button onclick="handleUpdateService()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">Simpan</button>
        </div>
    </div>
</div>

@vite('resources/js/api/services.js')

<script>
let currentEditServiceId = null;

async function renderServices() {
    const tbody = document.getElementById('serviceTableBody');
    console.log('Render services mulai...');

    const services = await fetchServices();
    console.log('Data service dari API:', services);

    if (!services.length) {
        tbody.innerHTML = `<tr>
            <td colspan="5" class="text-center py-4 text-gray-500">Belum ada data service.</td>
        </tr>`;
        return;
    }

    tbody.innerHTML = services.map((service, index) => `
        <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-4 text-gray-700 border-b">${index + 1}</td>
            <td class="px-4 py-4 text-gray-700 border-b">${service.name}</td>
            <td class="px-4 py-4 text-gray-700 border-b">Rp ${service.price.toLocaleString()}</td>
            <td class="px-4 py-4 text-gray-700 border-b">${service.type}</td>
            <td class="px-4 py-4 text-center border-b">
                <div class="flex justify-center items-center gap-4">
                    <button 
                        onclick="handleDetailService(${service.id})"
                        class="flex justify-center items-center rounded-lg border text-[#F5F5F5] font-inter text-[16px]"
                        style="background-color:#1E1E1E; border-color:#2C2C2C; width:64.5px; height:32px;"
                    >
                        Edit
                    </button>
                    <button 
                        onclick="handleDeleteService(${service.id})"
                        class="flex justify-center items-center rounded-lg border text-[#1E1E1E] font-inter text-[16px]"
                        style="background-color:#CDCDCD; border-color:#767676; width:64.5px; height:32px;"
                    >
                        Hapus
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

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
