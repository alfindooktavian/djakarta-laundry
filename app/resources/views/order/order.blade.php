@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="space-y-6">
    <x-title title="Order" />
    <x-add-button onclick="openModal('tambahOrderModal')">Tambah</x-add-button>

    <div class="bg-white shadow overflow-x-auto">
        <table class="min-w-full border-y border-gray-200 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">No</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Pelanggan</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">User</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Tanggal Order</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Status</th>
                    <th class="px-4 py-4 text-right text-sm font-semibold text-gray-700 border-b">Total Harga</th>
                    <th class="px-4 py-4 text-center text-sm font-semibold text-gray-700 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody id="orderTableBody">
                <tr>
                    <td colspan="7" class="text-center py-4 text-gray-500">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div id="paginationContainer" class="flex justify-end gap-2 mt-4"></div>
<!-- Modal Tambah Order -->
<x-modal id="tambahOrderModal" title="Tambah Order">
    <div class="flex flex-col gap-2">
        <!-- Customer Baru -->
        <button type="button" onclick="showNewCustomerForm()" 
            class="px-3 py-2 bg-gray-200 rounded hover:bg-gray-300 transition text-sm mb-2">
            + Customer Baru
        </button>

        <div id="newCustomerContainer" class="flex flex-col gap-2 mb-2 hidden">
            <input id="inputName" type="text" class="border rounded px-3 py-2 w-full" placeholder="Nama Customer" />
            <input id="inputPhone" type="text" class="border rounded px-3 py-2 w-full" placeholder="No. Telepon" />
            <input id="inputAddress" type="text" class="border rounded px-3 py-2 w-full" placeholder="Alamat" />
            <button type="button" onclick="handleCreateCustomerFromOrder()" 
                class="px-3 py-2 bg-black text-white rounded hover:bg-gray-800 transition text-sm">
                Simpan Customer
            </button>
        </div>

        <!-- Pilih Customer -->
        <div>
            <label class="text-gray-700">Pilih Customer</label>
            <select id="inputCustomerId" class="border rounded px-3 py-2 w-full">
                <option value="">Memuat data...</option>
            </select>
        </div>

        <!-- Tanggal Order -->
        <div>
            <label class="text-gray-700">Tanggal Order</label>
            <input id="inputOrderAt" type="date" class="border rounded px-3 py-2 w-full" />
        </div>

        <!-- Status -->
        <div>
            <label class="text-gray-700">Status</label>
            <select id="inputStatus" class="border rounded px-3 py-2 w-full">
                <option value="">Pilih Status</option>
                <option value="diterima">Diterima</option>
                <option value="diproses">Diproses</option>
                <option value="selesai">Selesai</option>
                <option value="diambil">Diambil</option>
            </select>
        </div>

        <!-- Detail Order -->
        <div class="border-t pt-4 mt-4">
            <h3 class="text-lg font-semibold mb-2">Detail Order</h3>
            <div id="orderDetailsContainer" class="flex flex-col gap-3"></div>
            <button type="button" onclick="addOrderDetailRow()" 
                class="mt-2 px-3 py-2 bg-gray-200 rounded hover:bg-gray-300 transition text-sm">
                + Tambah Layanan
            </button>
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('tambahOrderModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
        <button onclick="handleCreateOrder()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">Simpan</button>
    </x-slot>
</x-modal>


<!-- Modal Edit Order -->
<x-modal id="detailOrderModal" title="Edit Order">
    <div class="flex flex-col gap-3">
        {{-- === Info Customer === --}}
        <div>
            <label class="text-gray-700">Pilih Customer</label>
            <select id="detailCustomerId" class="border rounded px-3 py-2 w-full">
                <option value="">Pilih Customer...</option>
            </select>

            {{-- Info tambahan customer (otomatis tampil setelah dipilih) --}}
            <div id="customerInfoContainer" class="mt-2 text-sm text-gray-600"></div>
        </div>

        {{-- === Tanggal Order === --}}
        <div>
            <label class="text-gray-700">Tanggal Order</label>
            <input id="detailOrderAt" type="date" class="border rounded px-3 py-2 w-full" />
        </div>

        {{-- === Status === --}}
        <div>
            <label class="text-gray-700">Status</label>
            <select id="detailStatus" class="border rounded px-3 py-2 w-full">
                <option value="diterima">Diterima</option>
                <option value="diproses">Diproses</option>
                <option value="selesai">Selesai</option>
                <option value="diambil">Diambil</option>
            </select>
        </div>

        {{-- === Total Harga === --}}
        <div>
            <label class="text-gray-700">Total Harga</label>
            <input id="detailTotalPrice" type="number" class="border rounded px-3 py-2 w-full bg-gray-100" readonly />
        </div>
        {{-- === Detail Order === --}}
    <div class="border-t pt-4 mt-4">
        <h3 class="text-lg font-semibold mb-2">Detail Order</h3>
        <div id="editOrderDetailsContainer" class="flex flex-col gap-3"></div>
        <button type="button" onclick="addEditOrderDetailRow()" 
            class="mt-2 px-3 py-2 bg-gray-200 rounded hover:bg-gray-300 transition text-sm">
            + Tambah Layanan
        </button>
    </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('detailOrderModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
            Tutup
        </button>
        <button onclick="handleUpdateOrder()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">
            Simpan
        </button>
    </x-slot>
</x-modal>

<!-- Modal Pembayaran -->
<x-modal id="paymentModal" title="Pembayaran">
    <div class="flex flex-col gap-3">
        <input type="hidden" id="paymentOrderId" />
        
        <div>
            <label class="text-gray-700">Total Harga</label>
            <input id="paymentTotal" type="number" class="border rounded px-3 py-2 w-full bg-gray-100" readonly />
        </div>

        <div>
            <label class="text-gray-700">Metode Pembayaran</label>
            <select id="paymentMethod" class="border rounded px-3 py-2 w-full">
                <option value="cash">Cash</option>
                <option value="transfer">Transfer</option>
                <option value="ewallet">E-Wallet</option>
            </select>
        </div>

        <div>
            <label class="text-gray-700">Status</label>
            <select id="paymentStatus" class="border rounded px-3 py-2 w-full">
                <option value="unpaid">Belum Dibayar</option>
                <option value="paid">Sudah Dibayar</option>
            </select>
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('paymentModal')" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
        <button onclick="handleUpdatePayment()" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800">Simpan</button>
    </x-slot>
</x-modal>




@vite([
    'resources/js/api/orders.js',
    'resources/js/api/customers.js',
    'resources/js/api/order-details.js',
    'resources/js/api/services.js',
    'resources/js/api/payments.js' 
])

<script>
let currentEditOrderId = null;
let orderDetailCount = 0;
let deletedDetailIds = [];
let editDetailCount = 0; 
let currentPaymentId = null;
let lastPage = 1;

// ===== Tambah baris order detail =====
function addOrderDetailRow() {
    orderDetailCount++;
    const container = document.getElementById('orderDetailsContainer');

    const row = document.createElement('div');
    row.className = 'flex gap-2 items-center';
    row.dataset.index = orderDetailCount;

    row.innerHTML = `
        <select id="detailServiceId_${orderDetailCount}" class="border rounded px-2 py-1 w-1/2">
            <option value="">Pilih Service</option>
        </select>
        <input id="detailQuantity_${orderDetailCount}" type="number" min="1" value="1" class="border rounded px-2 py-1 w-20 text-center" />
        <input id="detailSubtotal_${orderDetailCount}" type="number" min="0" placeholder="Subtotal" class="border rounded px-2 py-1 w-32 text-right" readonly />
        <button type="button" onclick="removeOrderDetailRow(${orderDetailCount})" class="text-red-500 hover:text-red-700">Hapus</button>
    `;
    container.appendChild(row);

    loadServiceDropdown(`detailServiceId_${orderDetailCount}`);
}

// ===== Hapus baris detail =====
function removeOrderDetailRow(index) {
    const row = document.querySelector(`[data-index="${index}"]`);
    if (row) row.remove();
}

// ===== Load dropdown layanan =====
async function loadServiceDropdown(selectId) {
    // Ambil semua data tanpa pagination
    const services = await fetchServices(1, true); 

    const select = document.getElementById(selectId);
    select.innerHTML = '<option value="">Pilih Service</option>' +
        services.map(s => `<option value="${s.id}" data-price="${s.price}">${s.name}</option>`).join('');

    select.addEventListener('change', e => updateSubtotal(selectId));
    document.getElementById(`detailQuantity_${selectId.split('_')[1]}`).addEventListener('input', () => updateSubtotal(selectId));
}


// ===== Update subtotal =====
function updateSubtotal(selectId) {
    const index = selectId.split('_')[1];
    const serviceSelect = document.getElementById(selectId);
    const quantity = parseInt(document.getElementById(`detailQuantity_${index}`).value || 0);
    const price = parseInt(serviceSelect.selectedOptions[0]?.dataset.price || 0);
    const subtotal = price * quantity;

    document.getElementById(`detailSubtotal_${index}`).value = subtotal;
}

// ===== Hitung total =====
function calculateTotalPrice() {
    const subtotals = document.querySelectorAll('[id^="detailSubtotal_"]');
    let total = 0;
    subtotals.forEach(input => {
        total += parseInt(input.value || 0);
    });
    return total;
}

// ===== Render semua order =====
async function renderOrders(page = 1) {
    currentPage = page;
    const tbody = document.getElementById('orderTableBody');
    const { orders, current_page, last_page } = await fetchOrders(page, false); // fetchOrders perlu dikembalikan current_page + last_page
    lastPage = last_page;

    if (!orders.length) {
        tbody.innerHTML = `<tr>
            <td colspan="7" class="text-center py-4 text-gray-500">Belum ada data order.</td>
        </tr>`;
        renderPagination(current_page, last_page);
        return;
    }

    tbody.innerHTML = orders.map((order, index) => `
        <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-4 border-b">${(current_page - 1) * 5 + index + 1}</td>
            <td class="px-4 py-4 border-b">${order.customer?.name || '-'}</td>
            <td class="px-4 py-4 border-b">${order.user?.name || '-'}</td>
            <td class="px-4 py-4 border-b">${order.order_at}</td>
            <td class="px-4 py-4 border-b">${order.status || '-'}</td>
            <td class="px-4 py-4 border-b text-right">Rp ${order.total_price?.toLocaleString() || '0'}</td>
            <td class="px-4 py-4 border-b text-center">
                <div class="flex justify-center items-center gap-4">
                    <button onclick="handlePayment(${order.id})" class="flex justify-center items-center rounded-lg text-white px-2 h-8 bg-gradient-to-r from-blue-500 to-blue-400 border shadow-md hover:shadow-xl transform hover:scale-110 transition-all duration-300">
                        <iconify-icon icon="mdi:credit-card-outline" width="20" height="20" color="#FFFFFF"></iconify-icon>
                    </button>
                    <button onclick="handleDetailOrder(${order.id})" class="flex justify-center items-center rounded-lg text-white w-8 h-8 bg-gray-800 border shadow-md hover:shadow-xl transform hover:scale-110 transition-all duration-300">
                        <iconify-icon icon="mdi:eye-outline" width="20" height="20" color="#FFFFFF"></iconify-icon>
                    </button>
                    <button onclick="handleDeleteOrder(${order.id})" class="flex justify-center items-center rounded-lg text-white w-8 h-8 bg-red-600 border shadow-md hover:shadow-xl transform hover:scale-110 transition-all duration-300">
                        <iconify-icon icon="mdi:delete-outline" width="20" height="20" color="#FFFFFF"></iconify-icon>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');

    renderPagination(current_page, last_page);
}

// ===== Render Pagination =====
function renderPagination(currentPage, lastPage) {
    const container = document.getElementById('paginationContainer');
    if (!container) return; // jika belum ada container, skip

    let html = '';

    // Previous
    html += `<button onclick="gotoPage(${currentPage - 1})" class="px-3 py-1 border rounded hover:bg-gray-200 ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}">&lt;</button>`;

    let start = Math.max(currentPage - 1, 1);
    let end = Math.min(start + 2, lastPage);
    start = Math.max(end - 2, 1);

    for (let i = start; i <= end; i++) {
        html += `<button onclick="gotoPage(${i})" class="px-3 py-1 border rounded ${i === currentPage ? 'bg-gray-800 text-white' : 'hover:bg-gray-200'}">${i}</button>`;
    }

    // Next
    html += `<button onclick="gotoPage(${currentPage + 1})" class="px-3 py-1 border rounded hover:bg-gray-200 ${currentPage === lastPage ? 'opacity-50 cursor-not-allowed' : ''}">&gt;</button>`;

    container.innerHTML = html;
}

function gotoPage(page) {
    if (page < 1 || page > lastPage) return;
    renderOrders(page);
}


// ===== Load dropdown data =====
async function loadDropdownData() {
    // Ambil semua customer
    const data = await fetchCustomers(1, true); // all = true
    const list = Array.isArray(data) ? data : (data?.data || []);

    const customerOptions = list
        .map(c => `<option value="${c.id}">${c.name}</option>`)
        .join('');

    document.querySelectorAll('#inputCustomerId, #detailCustomerId').forEach(sel => {
        sel.innerHTML = `<option value="">Pilih Customer</option>${customerOptions}`;
    });
}


// ===== CREATE ORDER + DETAIL =====
async function handleCreateOrder() {
    const user = JSON.parse(localStorage.getItem('user'));
    const userId = user.id;

    const detailRows = document.querySelectorAll('#orderDetailsContainer > div');
    const details = [];

    for (let row of detailRows) {
        const index = row.dataset.index;
        const serviceId = document.getElementById(`detailServiceId_${index}`).value;
        const quantity = parseInt(document.getElementById(`detailQuantity_${index}`).value);
        const subtotal = parseInt(document.getElementById(`detailSubtotal_${index}`).value);
        if (!serviceId) continue;

        details.push({ service_id: serviceId, quantity, subtotal });
    }

    if(details.length === 0) return alert('Tambahkan minimal 1 detail order');

    const totalPrice = details.reduce((sum, d) => sum + d.subtotal, 0);

    const data = {
        customer_id: document.getElementById('inputCustomerId').value,
        user_id: userId,
        order_at: document.getElementById('inputOrderAt').value,
        status: document.getElementById('inputStatus').value,
        total_price: totalPrice,
        details: details // <-- kirim semua detail di sini
    };

    try {
        const orderRes = await createOrder(data);
        if (!orderRes || !orderRes.data?.id) {
            return alert('Gagal membuat order');
        }

        closeModal('tambahOrderModal');
        renderOrders();
    } catch (err) {
        console.error('Error createOrder', err);
        alert('Gagal membuat order: ' + err.message);
    }
}


// ===== Update Order =====
async function handleUpdateOrder() {
    if(!currentEditOrderId) return;

    const detailRows = document.querySelectorAll('#editOrderDetailsContainer > div');
    const details = [];

    for (let row of detailRows) {
        const index = row.dataset.index;
        const detailId = row.dataset.id || null;
        const serviceId = document.getElementById(`editServiceId_${index}`).value;
        const quantity = parseInt(document.getElementById(`editQuantity_${index}`).value);
        const subtotal = parseInt(document.getElementById(`editSubtotal_${index}`).value);
        if (!serviceId) continue;

        details.push({
            id: detailId,
            service_id: serviceId,
            quantity,
            subtotal
        });
    }

    const totalPrice = details.reduce((sum, d) => sum + d.subtotal, 0);

    const data = {
        customer_id: document.getElementById('detailCustomerId').value,
        order_at: document.getElementById('detailOrderAt').value,
        status: document.getElementById('detailStatus').value,
        total_price: totalPrice,
        details: details,
        deleted_details: deletedDetailIds // ✅ kirim array id yang dihapus
    };

    try {
        const res = await updateOrder(currentEditOrderId, data);
        if(res){
            deletedDetailIds = []; // ✅ reset array setelah simpan sukses
            closeModal('detailOrderModal');
            renderOrders();
        }
    } catch (err) {
        console.error('Error updateOrder', err);
        alert('Gagal memperbarui order');
    }
}





// ===== Delete Order =====
async function handleDeleteOrder(id) {
    if(confirm('Yakin ingin menghapus order ini?')){
        const res = await deleteOrder(id);
        if(res) renderOrders();
    }
}

// ===== Edit Order =====
async function handleDetailOrder(id) {
    const order = await fetchOrderById(id);
    if (!order) return alert('Order tidak ditemukan');

    currentEditOrderId = id;

    document.getElementById('detailCustomerId').value = order.customer_id;
    document.getElementById('detailOrderAt').value = order.order_at;
    document.getElementById('detailStatus').value = order.status || '';
    document.getElementById('detailTotalPrice').value = order.total_price || '';

    const container = document.getElementById('editOrderDetailsContainer');
    container.innerHTML = '';

    // 🔥 Ambil semua daftar service untuk dropdown
    const services = await fetchServices(1, true); // karena all = true, hasilnya array langsung

    if (order.order_details && order.order_details.length > 0) {
        for (const detail of order.order_details) {
            await addEditOrderDetailRow(detail, services);
        }
    }

    openModal('detailOrderModal');
}



function showNewCustomerForm() {
    document.getElementById('newCustomerContainer').classList.toggle('hidden');
}

async function handleCreateCustomerFromOrder() {
    const data = {
        name: document.getElementById('inputName').value.trim(),
        phone: document.getElementById('inputPhone').value.trim(),
        address: document.getElementById('inputAddress').value.trim(),
    };

    if (!data.name) return alert('Nama wajib diisi');

    try {
        const res = await createCustomer(data);
        console.log('Hasil create customer:', res);

        // Ambil customer dari response
        const createdCustomer = res.customer; // <-- di sini penting, sesuaikan dengan respons API

        if (!createdCustomer || !createdCustomer.id) return alert('Gagal menambahkan customer');

        // Tambahkan ke dropdown dan pilih otomatis
        const select = document.getElementById('inputCustomerId');
        const option = document.createElement('option');
        option.value = createdCustomer.id;
        option.text = createdCustomer.name;
        option.selected = true;
        select.appendChild(option);

        // Reset form
        document.getElementById('inputName').value = '';
        document.getElementById('inputPhone').value = '';
        document.getElementById('inputAddress').value = '';
        document.getElementById('newCustomerContainer').classList.add('hidden');

        alert('Customer berhasil ditambahkan'); // notif sukses
    } catch (err) {
        console.error('Error createCustomer', err);
        alert('Gagal menambahkan customer: ' + err.message);
    }
}

async function addEditOrderDetailRow(detail = null, services = null) {
    const container = document.getElementById('editOrderDetailsContainer');
    const index = ++editDetailCount;
    const row = document.createElement('div');
    row.className = 'flex gap-2 items-center';
    row.dataset.index = index;
    if (detail?.id) row.dataset.id = detail.id;

    // 🔥 Kalau services belum dikirim, ambil semua service (tanpa pagination)
    if (!services) {
        services = await fetchServices(1, true);
    }

    // 🔍 Pastikan kalau yang diterima bukan object pagination
    if (services.services) {
        services = services.services;
    }

    row.innerHTML = `
        <select id="editServiceId_${index}" class="border rounded px-2 py-1 w-1/2">
            <option value="">Pilih Service</option>
            ${services.map(s => `<option value="${s.id}" data-price="${s.price}">${s.name}</option>`).join('')}
        </select>
        <input id="editQuantity_${index}" type="number" min="1" value="${detail?.quantity ?? 1}" class="border rounded px-2 py-1 w-20 text-center" />
        <input id="editSubtotal_${index}" type="number" min="0" value="${detail?.subtotal ?? 0}" class="border rounded px-2 py-1 w-32 text-right" readonly />
        <button type="button" onclick="removeEditOrderDetailRow(${index})" class="text-red-500 hover:text-red-700">Hapus</button>
    `;

    container.appendChild(row);

    const selectEl = document.getElementById(`editServiceId_${index}`);
    if (detail?.service_id) selectEl.value = detail.service_id;

    selectEl.addEventListener('change', () => updateEditSubtotal(index));
    document.getElementById(`editQuantity_${index}`).addEventListener('input', () => updateEditSubtotal(index));

    updateEditSubtotal(index);
}



// Hapus baris edit (tandai id lama untuk dihapus di backend)
function removeEditOrderDetailRow(index) {
    const row = document.querySelector(`#editOrderDetailsContainer [data-index="${index}"]`);
    if (!row) return;

    const detailId = row.dataset.id;
    if (detailId) deletedDetailIds.push(detailId);

    row.remove();
    updateEditTotalPrice();
}


// // ===== Load dropdown layanan untuk EDIT =====
// // ===== Load dropdown layanan untuk EDIT =====
// async function loadEditServiceDropdown(selectId, selectedId = null) {
//     const services = await fetchServices();
//     const select = document.getElementById(selectId);
//     select.innerHTML = '<option value="">Pilih Service</option>' +
//         services.map(s => `<option value="${s.id}" data-price="${s.price}">${s.name}</option>`).join('');

//     if (selectedId) select.value = selectedId;

//     const index = selectId.split('_')[1];
//     select.addEventListener('change', () => updateEditSubtotal(selectId));
//     document.getElementById(`editQuantity_${index}`).addEventListener('input', () => updateEditSubtotal(selectId));

//     updateEditSubtotal(selectId);
// }


// Update subtotal berdasarkan index (angka)
function updateEditSubtotal(index) {
    const serviceSelect = document.getElementById(`editServiceId_${index}`);
    const quantityInput = document.getElementById(`editQuantity_${index}`);
    const subtotalInput = document.getElementById(`editSubtotal_${index}`);

    if (!serviceSelect || !quantityInput || !subtotalInput) return;

    // safe parsing: fallback ke 0 bila undefined / tidak bisa di parse
    const priceRaw = serviceSelect.selectedOptions[0]?.dataset?.price;
    const price = Number.isFinite(Number(priceRaw)) ? parseInt(priceRaw, 10) : 0;
    const quantity = Number.isFinite(Number(quantityInput.value)) ? parseInt(quantityInput.value, 10) : 0;

    const subtotal = price * quantity;
    subtotalInput.value = subtotal;

    updateEditTotalPrice();
}

// Hitung total untuk container edit saja
function updateEditTotalPrice() {
    const container = document.getElementById('editOrderDetailsContainer');
    const subtotals = container.querySelectorAll('[id^="editSubtotal_"]');
    let total = 0;
    subtotals.forEach(input => {
        const v = parseInt(input.value || 0, 10);
        total += Number.isFinite(v) ? v : 0;
    });
    document.getElementById('detailTotalPrice').value = total;
}

async function handlePayment(orderId) {
    document.getElementById('paymentOrderId').value = orderId;
    const order = await fetchOrderById(orderId);
    document.getElementById('paymentTotal').value = order.total_price;

    const payment = await fetchPaymentByOrderId(orderId);
    if (payment) {
        currentPaymentId = payment.id;
        document.getElementById('paymentMethod').value = payment.method;
        document.getElementById('paymentStatus').value = payment.status;
    } else {
        currentPaymentId = null;
        document.getElementById('paymentMethod').value = 'cash';
        document.getElementById('paymentStatus').value = 'unpaid';
    }

    openModal('paymentModal');
}

// Simpan pembayaran (update / create)
async function handleUpdatePayment() {
    const orderId = document.getElementById('paymentOrderId').value;
    const data = {
        method: document.getElementById('paymentMethod').value,
        status: document.getElementById('paymentStatus').value,
        amount: parseFloat(document.getElementById('paymentTotal').value)
    };

    let res;
    if (currentPaymentId) {
        res = await updatePayment(currentPaymentId, data);
    } else {
        res = await createPayment({ order_id: orderId, ...data });
    }

    if (res) {
        closeModal('paymentModal');
        alert('Pembayaran berhasil diperbarui');
    } else {
        alert('Gagal memperbarui pembayaran');
    }
}


// ==== Jalankan saat halaman dimuat ====
document.addEventListener('DOMContentLoaded', async () => {
    await loadDropdownData();
    await renderOrders();
});
</script>
@endsection
