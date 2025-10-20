<div id="sidebar"
    class="flex flex-col justify-between min-h-screen bg-white z-50 shadow-md transition-all duration-300 ease-in-out">

    <!-- Bagian atas: Logo dan navigasi -->
    <div>
        <!-- Logo / Brand -->
        <div class="text-2xl font-bold mt-6 mb-6 flex items-center justify-center md:justify-start px-2 md:px-4">
            <span id="sidebarLogo"></span>
        </div>

        <nav class="flex flex-col px-0 mt-4">
        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
            <iconify-icon icon="mdi:home-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Dashboard</span>
        </a>

        <a href="{{ route('administrator') }}"
            class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
            <iconify-icon icon="mdi:account-circle-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Administrator</span>
        </a>

        <a href="{{ route('service') }}"
            class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
            <iconify-icon icon="mdi:cog-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Layanan</span>
        </a>

        <a href="{{ route('customer') }}"
            class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
            <iconify-icon icon="mdi:account-group-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Pelanggan</span>
        </a>

        <a href="{{ route('orders') }}"
            class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
            <iconify-icon icon="mdi:cart-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Transaksi</span>
        </a>

        <a href="{{ route('chat') }}"
            class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
            <iconify-icon icon="mdi:message-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Chat</span>
        </a>

        <a href="{{ route('report') }}"
            class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
            <iconify-icon icon="mdi:file-chart-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
            <span class="sidebar-text hidden leading-none">Laporan</span>
        </a>
    </nav>
    </div>
    <!-- Bagian bawah Logout -->
    <div id="sidebarFooter"
        class="flex items-center justify-between bg-black text-white rounded-lg px-4 py-2 h-16 transition-all duration-300">

        <!-- Profil user -->
        <div id="userProfile" class="flex items-center gap-3 transition-all duration-300">
            <iconify-icon icon="mdi:account-circle-outline" width="30" height="42" class="text-white"></iconify-icon>
            <div class="leading-tight sidebar-text">
                <span id="userName" class="block text-base font-medium leading-none">User Name</span>
                <span id="userRole" class="block text-sm text-gray-400 leading-none">Role</span>
            </div>
        </div>

        <!-- Tombol logout -->
        <a href="#" id="logoutBtn" class="flex items-center justify-center text-white hover:text-gray-300 transition">
            <iconify-icon icon="mdi:logout" width="22" height="22" class="transform rotate-180"></iconify-icon>
        </a>
    </div>

</div>

<script>
    // Ambil data user dari localStorage
    const userData = localStorage.getItem("user");
    if (userData) {
        const user = JSON.parse(userData);
        document.getElementById("userName").textContent = user.name || "User";
        document.getElementById("userRole").textContent = user.role || "Role tidak diketahui";
    }

    // Logout event
    document.getElementById('logoutBtn').addEventListener('click', async (e) => {
        e.preventDefault();
        try {
            const response = await fetch("http://localhost:8000/api/logout", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${localStorage.getItem("api_token")}`
                },
            });

            if (!response.ok) throw new Error("Logout gagal");

            localStorage.removeItem("api_token");
            localStorage.removeItem("user");
            window.location.href = "/login";
        } catch (error) {
            console.error(error);
            alert("Logout gagal, silakan coba lagi.");
        }
    });
</script>