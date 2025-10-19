<div id="sidebar" class="flex-shrink-0 min-h-screen bg-white z-50 shadow-md flex flex-col transition-all duration-300 sidebar-collapsed w-16">
    <!-- Logo / Brand -->
    <div class="text-2xl font-bold mt-6 mb-6 flex items-center justify-center md:justify-start px-2 md:px-4">
        <span id="sidebarLogo"></span>
    </div>

    <!-- Navigation -->
    <nav class="flex flex-col px-0 mt-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
            <iconify-icon icon="mdi:home-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Dashboard</span>
        </a>

        <a href="{{ route('administrator') }}" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
            <iconify-icon icon="mdi:account-circle-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Administrator</span>
        </a>

        <a href="{{ route('service') }}" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
            <iconify-icon icon="mdi:cog-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Layanan</span>
        </a>

        <a href="{{ route('customer') }}" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
            <iconify-icon icon="mdi:account-group-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Pelanggan</span>
        </a>

        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
            <iconify-icon icon="mdi:cart-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Transaksi</span>
        </a>

        <a href="{{ route('chat') }}" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
            <iconify-icon icon="mdi:message-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Chat</span>
        </a>

        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
            <iconify-icon icon="mdi:file-chart-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Laporan</span>
        </a>

        <!-- Tombol Logout -->
        <button id="logoutBtn" class="flex items-center gap-3 text-red-500 hover:text-red-700 font-medium text-base h-10 px-4 rounded-md mt-4 text-left">
            <iconify-icon icon="mdi:logout" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Logout</span>
        </button>
    </nav>
</div>

{{-- Tambahkan di akhir sidebar --}}
@vite('resources/js/api/auth.js')

<script type="module">
    const logoutBtn = document.getElementById('logoutBtn');
    logoutBtn.addEventListener('click', async (e) => {
        e.preventDefault();

        const result = await window.logoutUser();

        if (result.error) {
            alert(result.error);
        } else {
            // Setelah logout berhasil
            window.location.href = "/login";
        }
    });
</script>
