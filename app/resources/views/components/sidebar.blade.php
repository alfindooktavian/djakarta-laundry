<div id="sidebar" class="flex-shrink-0 min-h-screen bg-white z-50 shadow-md flex flex-col transition-all duration-300 sidebar-collapsed w-16">
    <!-- Logo / Brand -->
    <div class="text-2xl font-bold mt-6 mb-6 flex items-center justify-center md:justify-start px-2 md:px-4">
    <span id="sidebarLogo"></span>
</div>


    <!-- Navigation -->
    <nav class="flex flex-col  px-0 mt-4">
    <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
        <iconify-icon icon="mdi:home-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
        <span class="sidebar-text hidden leading-none">Dashboard</span>
    </a>
    <a href="{{ route('administrator') }}" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
    <iconify-icon icon="mdi:account-circle-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
    <span class="sidebar-text hidden leading-none">Administrator</span>
</a>

    <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
        <iconify-icon icon="mdi:cog-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
        <span class="sidebar-text hidden leading-none">Layanan</span>
    </a>
    <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
        <iconify-icon icon="mdi:account-group-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
        <span class="sidebar-text hidden leading-none">Pelanggan</span>
    </a>
    <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
        <iconify-icon icon="mdi:cart-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
        <span class="sidebar-text hidden leading-none">Transaksi</span>
    </a>
    <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium text-base h-10 px-4 rounded-md">
        <iconify-icon icon="mdi:file-chart-outline" width="24" height="24" class="flex-shrink-0"></iconify-icon>
        <span class="sidebar-text hidden leading-none">Laporan</span>
    </a>
        

<script>
    document.getElementById('logoutBtn').addEventListener('click', async (e) => {
        e.preventDefault();

        try {
            // Ganti URL ini dengan endpoint backend logout yang sebenarnya
            const response = await fetch("http://localhost:8000/api/logout", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${localStorage.getItem("api_token")}`
                },
                body: JSON.stringify({})
            });

            if (!response.ok) {
                throw new Error("Logout gagal");
            }

            // Bersihkan localStorage
            localStorage.removeItem("api_token");
            localStorage.removeItem("user");

            // Redirect ke halaman login
            window.location.href = "/login";
        } catch (error) {
            console.error(error);
            alert("Logout gagal, silakan coba lagi.");
        }
    });
</script>

    </nav>
</div>
