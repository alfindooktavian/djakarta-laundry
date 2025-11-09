@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div id="userInfo" class="mb-6 text-gray-800"></div>

{{-- Grid Card --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <x-card id="card-customers" title="Total Pelanggan" value="0" icon="mdi:account-group" color="green" 
        class="bg-green-100 border border-green-200 text-green-700 shadow-sm hover:shadow-md transition" />
    <x-card id="card-services" title="Total Layanan" value="0" icon="mdi:washing-machine" color="blue"
        class="bg-blue-100 border border-blue-200 text-blue-700 shadow-sm hover:shadow-md transition" />
    <x-card id="card-active" title="Pesanan Berjalan" value="0" icon="mdi:progress-clock" color="yellow"
        class="bg-yellow-100 border border-yellow-200 text-yellow-700 shadow-sm hover:shadow-md transition" />
    <x-card id="card-completed" title="Pesanan Selesai" value="0" icon="mdi:check-circle-outline" color="purple"
        class="bg-purple-100 border border-purple-200 text-purple-700 shadow-sm hover:shadow-md transition" />
</div>

{{-- Chart --}}
<div class="w-full">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
            <x-chart
                id="incomeChart"
                title="Penghasilan Bulanan"
                :years="[2021, 2022, 2023, 2024, 2025]"
                :incomeData="[ /* bisa diisi dinamis nanti */ ]"
            />
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
            {{-- Piechart Ringkasan Layanan Dinamis --}}
            <x-piechart
                id="summaryChart"
                title="Ringkasan Layanan"
                :years="[]"
                :summaryData="[]"
            />
        </div>
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
            <div class="p-4 bg-gradient-to-r from-green-100 to-blue-100 rounded-xl shadow-sm mb-4">
                <h1 class="text-3xl font-bold text-gray-900 mb-1">
                    Selamat datang, <span class="text-green-700">${user.name}</span> 👋
                </h1>
                <p class="text-lg text-gray-600 font-medium">Djakarta Laundry</p>
            </div>
        `;
    }

    document.getElementById("logoutBtn")?.addEventListener("click", () => {
        localStorage.removeItem("api_token");
        localStorage.removeItem("user");
        window.location.href = "/login";
    });

    document.addEventListener("DOMContentLoaded", async () => {
        const data = await fetchDashboard();

        document.querySelector('#card-customers .card-value').textContent = data.total_customers;
        document.querySelector('#card-services .card-value').textContent = data.total_services;
        document.querySelector('#card-active .card-value').textContent = data.active_orders;
        document.querySelector('#card-completed .card-value').textContent = data.completed_orders;
    });
</script>
@endsection
