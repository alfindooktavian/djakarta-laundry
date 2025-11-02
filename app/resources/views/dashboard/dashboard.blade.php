@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div id="userInfo" class="mb-6 text-gray-800"></div>

    {{-- Grid Card --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-card title="Total Pelanggan" value="120" icon="mdi:account-group" color="gray" />
        <x-card title="Total Layanan" value="15" icon="mdi:washing-machine" color="gray" />
        <x-card title="Pesanan Berjalan" value="25" icon="mdi:progress-clock" color="gray" />
        <x-card title="Pesanan Selesai" value="60" icon="mdi:check-circle-outline" color="gray" />
    </div>

    
    <div class="w-full">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <!-- Chart Penghasilan Bulanan -->
    <x-chart
        id="incomeChart"
        title="Penghasilan Bulanan"
        :years="[2021, 2022, 2023, 2024, 2025]"
        :incomeData="[
            2021 => [1200000, 1400000, 1600000, 1800000, 2000000, 2200000, 2500000, 2600000, 2700000, 2900000, 3100000, 3300000],
            2022 => [1300000, 1500000, 1700000, 1900000, 2100000, 2300000, 2600000, 2700000, 2800000, 3000000, 3200000, 3400000],
            2023 => [1400000, 1600000, 1800000, 2000000, 2200000, 2400000, 2700000, 2800000, 2900000, 3100000, 3300000, 3500000],
            2024 => [1500000, 1700000, 1900000, 2100000, 2300000, 2500000, 2800000, 2900000, 3000000, 3200000, 3400000, 3600000],
            2025 => [1600000, 1800000, 2000000, 2200000, 2400000, 2600000, 2900000, 3000000, 3100000, 3300000, 3500000, 3700000],
        ]"
    />

    <x-piechart
    id="summaryChart"
    title="Ringkasan Layanan"
    :years="[2021, 2022, 2023, 2024, 2025]"
    :summaryData="[
        2021 => ['layanan' => 50, 'pesanan' => 30, 'customer' => 20],
        2022 => ['layanan' => 60, 'pesanan' => 35, 'customer' => 25],
        2023 => ['layanan' => 70, 'pesanan' => 40, 'customer' => 30],
        2024 => ['layanan' => 80, 'pesanan' => 45, 'customer' => 35],
        2025 => ['layanan' => 90, 'pesanan' => 50, 'customer' => 40],
    ]"
/>
</div>
</div>


    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    <script>
        // Ambil data user dari localStorage
        const user = JSON.parse(localStorage.getItem("user"));
        const token = localStorage.getItem("api_token");

        if (!user || !token) {
            window.location.href = "/login";
        } else {
            document.getElementById("userInfo").innerHTML = `
                <h1 class="text-3xl font-bold text-gray-900 mb-1">
                    Selamat datang, <span class="text-gray-800">${user.name}</span> 👋
                </h1>
                <p class="text-xl text-gray-600 font-medium">
                    Djakarta Laundry
                </p>
            `;
        }

        // Tombol logout
        document.getElementById("logoutBtn").addEventListener("click", () => {
            localStorage.removeItem("api_token");
            localStorage.removeItem("user");
            window.location.href = "/login";
        });
    </script>
@endsection
