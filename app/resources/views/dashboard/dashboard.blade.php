@extends('layouts.app')

@section('title', 'Dashboard')

@section('content') <div id="userInfo" class="mb-6 text-gray-800"></div>


{{-- Grid Card --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <x-card id="card-customers" title="Total Pelanggan" value="0" icon="mdi:account-group" color="gray" />
    <x-card id="card-services" title="Total Layanan" value="0" icon="mdi:washing-machine" color="gray" />
    <x-card id="card-active" title="Pesanan Berjalan" value="0" icon="mdi:progress-clock" color="gray" />
    <x-card id="card-completed" title="Pesanan Selesai" value="0" icon="mdi:check-circle-outline" color="gray" />
</div>

{{-- Chart --}}
<div class="w-full">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <x-chart
            id="incomeChart"
            title="Penghasilan Bulanan"
            :years="[2021, 2022, 2023, 2024, 2025]"
            :incomeData="[ /* bisa diisi dinamis nanti */ ]"
        />

        {{-- Piechart Ringkasan Layanan Dinamis --}}
        <x-piechart
            id="summaryChart"
            title="Ringkasan Layanan"
            :years="[]" {{-- kosong, nanti JS isi --}}
            :summaryData="[]" {{-- kosong, nanti JS isi --}}
        />
    </div>
</div>

<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@vite('resources/js/api/dashboard.js')

<script>
    const user = JSON.parse(localStorage.getItem("user"));
    const token = localStorage.getItem("api_token");

    if (!user || !token) {
        window.location.href = "/login";
    } else {
        document.getElementById("userInfo").innerHTML = `
            <h1 class="text-3xl font-bold text-gray-900 mb-1">
                Selamat datang, <span class="text-gray-800">${user.name}</span> 👋
            </h1>
            <p class="text-xl text-gray-600 font-medium">Djakarta Laundry</p>
        `;
    }

    document.getElementById("logoutBtn")?.addEventListener("click", () => {
        localStorage.removeItem("api_token");
        localStorage.removeItem("user");
        window.location.href = "/login";
    });

    document.addEventListener("DOMContentLoaded", async () => {
    const data = await fetchDashboard();

    // Update card
    document.querySelector('#card-customers .card-value').textContent = data.total_customers;
    document.querySelector('#card-services .card-value').textContent = data.total_services;
    document.querySelector('#card-active .card-value').textContent = data.active_orders;
    document.querySelector('#card-completed .card-value').textContent = data.completed_orders;

    // // Piechart
    // const chartId = 'summaryChart';
    // const chartCanvas = document.getElementById(chartId);
    // const ctx = chartCanvas.getContext('2d');
    // let chartInstance = null;

    // // Ambil select dari Blade
    // const yearSelect = document.getElementById(chartId + 'YearSelect');

    // // Kosongkan option lama, sisakan default
    // yearSelect.innerHTML = '<option value="">Semua Tahun</option>';

    // // Buat array tahun unik dari semua sumber
    // const years = Array.from(new Set([
    //     ...data.orders_per_year.map(o => o.year),
    //     ...data.customers_per_year.map(c => c.year),
    //     ...data.services_per_year.map(s => s.year)
    // ])).sort((a,b) => a-b);

    // // Append tahun unik ke dropdown
    // years.forEach(y => {
    //     const option = document.createElement('option');
    //     option.value = y;
    //     option.textContent = y;
    //     yearSelect.appendChild(option);
    // });

    // // Render piechart
    // async function loadSummaryChart(year = null) {
    //     const chartDataAPI = await fetchDashboard(year);
    //     const chartData = [
    //         chartDataAPI.total_services,
    //         chartDataAPI.total_orders,
    //         chartDataAPI.total_customers
    //     ];

    //     if (chartInstance) chartInstance.destroy();

    //     chartInstance = new Chart(ctx, {
    //         type: 'pie',
    //         data: {
    //             labels: ['Total Services', 'Total Orders', 'Total Customers'],
    //             datasets: [{
    //                 data: chartData,
    //                 backgroundColor: [
    //                     'rgba(107, 114, 128, 0.8)',
    //                     'rgba(156, 163, 175, 0.8)',
    //                     'rgba(209, 213, 219, 0.8)'
    //                 ],
    //                 borderColor: [
    //                     'rgba(107, 114, 128, 1)',
    //                     'rgba(156, 163, 175, 1)',
    //                     'rgba(209, 213, 219, 1)'
    //                 ],
    //                 borderWidth: 1
    //             }]
    //         },
    //         options: {
    //             maintainAspectRatio: false,
    //             responsive: true,
    //             plugins: {
    //                 legend: { position: 'bottom', labels: { font: { size: 12 }, color: '#6b7280' } },
    //                 tooltip: {
    //                     callbacks: {
    //                         label: function(context) {
    //                             return context.label + ": " + context.raw.toLocaleString();
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     });
    // }

    // yearSelect.addEventListener('change', e => loadSummaryChart(e.target.value));

    // // Render awal
    // loadSummaryChart(yearSelect.value || null);
});

</script>


@endsection
