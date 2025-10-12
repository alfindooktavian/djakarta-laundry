@extends('layouts.app')

@section('title', 'Customers')

@section('content')
<div class="space-y-6">
    <x-title title="Pelanggan" />
    <x-add-button onclick="openModal('tambahCustomerModal')">Tambah</x-add-button>

    <div class="bg-white shadow overflow-x-auto">
        <table class="min-w-full border-y border-gray-200 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">No</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Nama</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Telepon</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Alamat</th>
                    <th class="px-4 py-4 text-center text-sm font-semibold text-gray-700 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody id="customerTableBody">
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Customer -->
<div id="tambahCustomerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6 relative">
        <button onclick="closeModal('tambahCustomerModal')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
            <iconify-icon icon="mdi:close" width="24" height="24"></iconify-icon>
        </button>
        <h2 class="text-xl font-semibold mb-4">Tambah Customer</h2>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Nama</label>
            <input id="inputName" type="text" name="name" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Nama Pelanggan" />
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Telepon</label>
            <input id="inputPhone" type="text" name="phone" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Nomor Telepon" />
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Alamat</label>
            <textarea id="inputAddress" name="address" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Alamat"></textarea>
        </div>

        <div class="flex justify-end gap-2">
            <button onclick="closeModal('tambahCustomerModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Batal</button>
            <button onclick="handleCreateCustomer()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">Simpan</button>
        </div>
    </div>
</div>

<!-- Modal Detail / Edit Customer -->
<div id="detailCustomerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6 relative">
        <button onclick="closeModal('detailCustomerModal')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
            <iconify-icon icon="mdi:close" width="24" height="24"></iconify-icon>
        </button>
        <h2 class="text-xl font-semibold mb-4">Edit Customer</h2>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Nama</label>
            <input id="detailName" type="text" class="border rounded px-3 py-2 w-full" />
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Telepon</label>
            <input id="detailPhone" type="text" class="border rounded px-3 py-2 w-full" />
        </div>

        <div class="flex flex-col gap-1 mb-4">
            <label class="text-gray-700">Alamat</label>
            <textarea id="detailAddress" class="border rounded px-3 py-2 w-full"></textarea>
        </div>

        <div class="flex justify-end gap-2">
            <button onclick="closeModal('detailCustomerModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">Tutup</button>
            <button onclick="handleUpdateCustomer()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">Simpan</button>
        </div>
    </div>
</div>

@vite('resources/js/api/customers.js')

<script>
let currentEditCustomerId = null;

// Render semua data customer
async function renderCustomers() {
    const tbody = document.getElementById('customerTableBody');
    console.log('Render customers mulai...');

    const customers = await fetchCustomers();
    console.log('Data customer dari API:', customers);

    if (!customers.length) {
        tbody.innerHTML = `<tr>
            <td colspan="5" class="text-center py-4 text-gray-500">Belum ada data pelanggan.</td>
        </tr>`;
        return;
    }

    tbody.innerHTML = customers.map((customer, index) => `
        <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-4 text-gray-700 border-b">${index + 1}</td>
            <td class="px-4 py-4 text-gray-700 border-b">${customer.name}</td>
            <td class="px-4 py-4 text-gray-700 border-b">${customer.phone || '-'}</td>
            <td class="px-4 py-4 text-gray-700 border-b">${customer.address || '-'}</td>
            <td class="px-4 py-4 text-center border-b">
                <div class="flex justify-center items-center gap-4">
                    <button 
                        onclick="handleDetailCustomer(${customer.id})"
                        class="flex justify-center items-center rounded-lg border text-[#F5F5F5] font-inter text-[16px]"
                        style="background-color:#1E1E1E; border-color:#2C2C2C; width:64.5px; height:32px;"
                    >
                        Edit
                    </button>
                    <button 
                        onclick="handleDeleteCustomer(${customer.id})"
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

// Tambah customer baru
async function handleCreateCustomer() {
    const data = {
        name: document.getElementById('inputName').value,
        phone: document.getElementById('inputPhone').value,
        address: document.getElementById('inputAddress').value,
    };

    const res = await createCustomer(data);
    console.log('Hasil create customer:', res);
    if(res){
        closeModal('tambahCustomerModal');
        renderCustomers();
    }
}

// Hapus customer
async function handleDeleteCustomer(id) {
    if(confirm('Yakin ingin menghapus customer ini?')){
        const res = await deleteCustomer(id);
        console.log('Hasil delete customer:', res);
        if(res){
            renderCustomers();
        }
    }
}

// Detail/Edit customer
async function handleDetailCustomer(id) {
    const customer = await fetchCustomerById(id);
    if(!customer) return alert('Customer tidak ditemukan');

    currentEditCustomerId = id;

    document.getElementById('detailName').value = customer.name;
    document.getElementById('detailPhone').value = customer.phone || '';
    document.getElementById('detailAddress').value = customer.address || '';

    openModal('detailCustomerModal');
}

// Update data customer
async function handleUpdateCustomer() {
    if(!currentEditCustomerId) return;

    const data = {
        name: document.getElementById('detailName').value,
        phone: document.getElementById('detailPhone').value,
        address: document.getElementById('detailAddress').value,
    };

    const res = await updateCustomer(currentEditCustomerId, data);
    console.log('Hasil update customer:', res);

    if(res){
        closeModal('detailCustomerModal');
        renderCustomers();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    renderCustomers();
});
</script>
@endsection
