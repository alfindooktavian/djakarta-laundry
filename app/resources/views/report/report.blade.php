@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="space-y-6">
    <x-title title="Laporan Transaksi" />
    <x-add-button onclick="openModal('downloadReportModal')">Download</x-add-button>

    <!-- Table -->
    <div class="bg-white shadow overflow-x-auto">
        <table class="min-w-full border-y border-gray-200 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">No</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Pelanggan</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Kasir</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Tanggal Order</th>
                    <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700 border-b">Status</th>
                    <th class="px-4 py-4 text-right text-sm font-semibold text-gray-700 border-b">Total Harga</th>
                </tr>
            </thead>
            <tbody id="reportTableBody">
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Download -->
<!-- Modal Download Laporan -->
<x-modal id="downloadReportModal" title="Download Laporan Transaksi">
    <div class="flex flex-col gap-4">
        <!-- Pilihan Periode -->
        <div>
            <label class="text-gray-700 font-medium">Pilih Periode</label>
            <select id="reportPeriod" class="border rounded px-3 py-2 w-full">
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
                <input type="date" id="startDate" class="border rounded px-3 py-2 w-full" />
            </div>
            <div>
                <label class="text-gray-700 font-medium">Tanggal Selesai</label>
                <input type="date" id="endDate" class="border rounded px-3 py-2 w-full" />
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <button onclick="closeModal('downloadReportModal')" 
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">
            Batal
        </button>
        <button onclick="handleDownloadPDF()" 
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
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
    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-gray-500">Memuat data...</td></tr>`;

    const reports = await fetchReports();

    if (!reports.length) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-gray-500">Belum ada data transaksi.</td></tr>`;
        return;
    }

    tbody.innerHTML = reports.map((order, index) => `
        <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-4 text-gray-700 border-b">${index + 1}</td>
            <td class="px-4 py-4 text-gray-700 border-b">${order.customer?.name ?? '-'}</td>
            <td class="px-4 py-4 text-gray-700 border-b">${order.user?.name ?? '-'}</td>
            <td class="px-4 py-4 text-gray-700 border-b">
                ${order.order_at ? new Date(order.order_at).toLocaleDateString('id-ID', {
                    day: '2-digit', month: '2-digit', year: 'numeric'
                }) : '-'}
            </td>
            <td class="px-4 py-4 text-gray-700 border-b">${order.status ?? '-'}</td>
            <td class="px-4 py-4 text-right text-gray-700 border-b">
                Rp ${Number(order.total_price ?? 0).toLocaleString('id-ID')}
            </td>
        </tr>
    `).join('');
}
</script>
@endsection
