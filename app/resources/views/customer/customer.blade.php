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
<x-modal id="tambahCustomerModal" title="Tambah Customer">
    <div class="flex flex-col gap-2">
        <div>
            <label class="text-gray-700">Nama</label>
            <input id="inputName" type="text" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Nama Pelanggan" />
        </div>
        <div>
            <label class="text-gray-700">Telepon</label>
            <input id="inputPhone" type="text" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Nomor Telepon" />
        </div>
        <div>
            <label class="text-gray-700">Alamat</label>
            <textarea id="inputAddress" class="border rounded px-3 py-2 w-full" placeholder="Masukkan Alamat"></textarea>
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('tambahCustomerModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
        <button onclick="handleCreateCustomer()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">Simpan</button>
    </x-slot>
</x-modal>


<!-- Modal Detail / Edit Customer -->
<x-modal id="detailCustomerModal" title="Edit Customer">
    <div class="flex flex-col gap-2">
        <div>
            <label class="text-gray-700">Nama</label>
            <input id="detailName" type="text" class="border rounded px-3 py-2 w-full" />
        </div>
        <div>
            <label class="text-gray-700">Telepon</label>
            <input id="detailPhone" type="text" class="border rounded px-3 py-2 w-full" />
        </div>
        <div>
            <label class="text-gray-700">Alamat</label>
            <textarea id="detailAddress" class="border rounded px-3 py-2 w-full"></textarea>
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('detailCustomerModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
        <button onclick="handleUpdateCustomer()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">Simpan</button>
    </x-slot>
</x-modal>


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
                        class="flex justify-center items-center rounded-lg text-white w-8 h-8
               bg-gray-800 border border-gray-700 shadow-md hover:shadow-xl
               transform hover:scale-110 transition-all duration-300"
    >
        <iconify-icon class="transition-transform transform hover:rotate-12 hover:scale-125" 
                       icon="mdi:eye-outline" width="20" height="20" color="#FFFFFF">
        </iconify-icon>
                    </button>
                    <button 
    onclick="handleDeleteCustomer(${customer.id})"
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
