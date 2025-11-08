@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="container mx-auto py-6 space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-2xl font-semibold text-gray-800">Laporan Transaksi</h3>
        </div>

        <button onclick="openModal('downloadReportModal')"
            class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>
            Download
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Pelanggan</th>
                    <th class="px-4 py-3 text-left font-semibold">Kasir</th>
                    <th class="px-4 py-3 text-left font-semibold">Tanggal Order</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-right font-semibold">Total Harga</th>
                </tr>
            </thead>
            <tbody id="reportTableBody">
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Download -->
<x-modal id="downloadReportModal" title="Download Laporan Transaksi">
    <div class="flex flex-col gap-4">
        <!-- Pilih Periode -->
        <div>
            <label class="text-gray-700 font-medium">Pilih Periode</label>
            <select id="reportPeriod" class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400">
                <option value="weekly">Mingguan</option>
                <option value="monthly">Bulanan</option>
                <option value="yearly">Tahunan</option>
                <option value="custom">Custom</option>
            </select>
        </div>

        <!-- Custom Date Range -->
        <div id="customDateFields" class="hidden flex flex-col gap-2">
            <div>
                <label class="text-gray-700 font-medium">Tanggal Mulai</label>
                <input type="date" id="startDate"
                    class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400" />
            </div>
            <div>
                <label class="text-gray-700 font-medium">Tanggal Selesai</label>
                <input type="date" id="endDate"
                    class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-400" />
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('downloadReportModal')"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
            Batal
        </button>
        <button onclick="handleDownloadPDF()"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Download PDF
        </button>
    </x-slot>
</x-modal>

@vite('resources/js/api/report.js')

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.getElementById(id).classList.add('flex');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');
}

document.addEventListener('DOMContentLoaded', () => {
    const periodSelect = document.getElementById('reportPeriod');
    const customFields = document.getElementById('customDateFields');

    periodSelect.addEventListener('change', () => {
        customFields.classList.toggle('hidden', periodSelect.value !== 'custom');
    });

    renderReports();
});

function handleDownloadPDF() {
    const period = document.getElementById('reportPeriod').value;
    let params = `period=${period}`;

    if (period === 'custom') {
        const start = document.getElementById('startDate').value;
        const end = document.getElementById('endDate').value;

        if (!start || !end) {
            alert('Isi tanggal mulai dan tanggal selesai dulu bro!');
            return;
        }

        params += `&start_date=${start}&end_date=${end}`;
    }

    downloadReport('pdf', params);
    closeModal('downloadReportModal');
}

async function renderReports() {
    const tbody = document.getElementById('reportTableBody');
    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-6 text-gray-500">Memuat data...</td></tr>`;

    const reports = await fetchReports();

    if (!reports.length) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-6 text-gray-500">Belum ada data transaksi.</td></tr>`;
        return;
    }

    tbody.innerHTML = reports.map((order, index) => `
        <tr class="hover:bg-blue-50 transition">
            <td class="px-4 py-3 border-b border-gray-200">${index + 1}</td>
            <td class="px-4 py-3 border-b border-gray-200">${order.customer?.name ?? '-'}</td>
            <td class="px-4 py-3 border-b border-gray-200">${order.user?.name ?? '-'}</td>
            <td class="px-4 py-3 border-b border-gray-200">
                ${order.order_at ? new Date(order.order_at).toLocaleDateString('id-ID', {
                    day: '2-digit', month: '2-digit', year: 'numeric'
                }) : '-'}
            </td>
            <td class="px-4 py-3 border-b border-gray-200">
                <span class="px-2 py-1 text-xs rounded-full ${getStatusColor(order.status)}">${order.status ?? '-'}</span>
            </td>
            <td class="px-4 py-3 text-right border-b border-gray-200 font-semibold text-gray-800">
                Rp ${Number(order.total_price ?? 0).toLocaleString('id-ID')}
            </td>
        </tr>
    `).join('');
}

// Warna status
function getStatusColor(status) {
    switch (status?.toLowerCase()) {
        case 'diterima':
            return 'bg-blue-100 text-blue-800';
        case 'diproses':
            return 'bg-yellow-100 text-yellow-800';
        case 'selesai':
            return 'bg-green-100 text-green-800';
        case 'diambil':
            return 'bg-purple-100 text-purple-800';
        default:
            return 'bg-gray-100 text-gray-700';
    }
}
</script>
@endsection
