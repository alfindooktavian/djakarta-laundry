<div id="sidebar"
    class="flex flex-col justify-between h-screen sticky top-0 bg-white z-50 shadow-md transition-all duration-300 ease-in-out">

    <!-- Bagian atas: Logo & navigasi -->
    <div>
        <!-- Logo -->
        <div class="text-2xl font-bold mt-6 mb-6 flex items-center justify-center md:justify-start px-2 md:px-4">
            <span id="sidebarLogo">🧺 Djakarta Laundry</span>
        </div>

        <!-- Navigasi -->
        <nav class="flex flex-col px-0 mt-4">
            <a href="{{ route('dashboard') }}"
                class="nav-link flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
                <iconify-icon icon="mdi:home-outline" width="22" height="22" class="flex-shrink-0"></iconify-icon>
                <span class="sidebar-text hidden leading-none">Dashboard</span>
            </a>

            <a href="{{ route('administrator') }}"
                class="nav-link flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
                <iconify-icon icon="mdi:account-circle-outline" width="22" height="22"></iconify-icon>
                <span class="sidebar-text hidden leading-none">Administrator</span>
            </a>

            <a href="{{ route('service') }}"
                class="nav-link flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
                <iconify-icon icon="mdi:cog-outline" width="22" height="22"></iconify-icon>
                <span class="sidebar-text hidden leading-none">Layanan</span>
            </a>

            <a href="{{ route('customer') }}"
                class="nav-link flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
                <iconify-icon icon="mdi:account-group-outline" width="22" height="22"></iconify-icon>
                <span class="sidebar-text hidden leading-none">Pelanggan</span>
            </a>

            <a href="{{ route('order') }}"
                class="nav-link flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
                <iconify-icon icon="mdi:cart-outline" width="22" height="22"></iconify-icon>
                <span class="sidebar-text hidden leading-none">Transaksi</span>
            </a>

            <a href="{{ route('chat') }}"
                class="nav-link flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
                <iconify-icon icon="mdi:message-outline" width="22" height="22"></iconify-icon>
                <span class="sidebar-text hidden leading-none">Chat</span>
            </a>

            <a href="{{ route('report') }}"
                class="nav-link flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
                <iconify-icon icon="mdi:file-chart-outline" width="22" height="22"></iconify-icon>
                <span class="sidebar-text hidden leading-none">Laporan</span>
            </a>
        </nav>
    </div>

    <!-- Bagian bawah: User & Logout -->
    <div class="p-2 border-t border-gray-100">
        <div id="sidebarFooter"
            class="flex items-center justify-between bg-black text-white rounded-lg px-4 py-2 h-14 transition-all duration-300">
            <div id="userProfile" class="flex items-center gap-3">
                <iconify-icon icon="mdi:account-circle-outline" width="30" height="30" class="text-white"></iconify-icon>
                <div class="leading-tight sidebar-text">
                    <span id="userName" class="block text-base font-medium leading-none">User</span>
                    <span id="userRole" class="block text-sm text-gray-400 leading-none">Role</span>
                </div>
            </div>

            <button id="logoutBtn" class="text-white hover:text-gray-300 transition" title="Logout">
                <iconify-icon icon="mdi:logout" width="22" height="22" class="transform rotate-180"></iconify-icon>
            </button>
        </div>
    </div>
</div>

@vite('resources/js/components/sidebar.js')
