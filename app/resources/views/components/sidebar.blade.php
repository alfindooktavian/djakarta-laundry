<div id="sidebar" class="flex-shrink-0 min-h-screen bg-white shadow-md flex flex-col transition-all duration-300 sidebar-collapsed w-16">
    <!-- Logo / Brand -->
    <div class="text-2xl font-bold mb-6 flex items-center justify-center md:justify-start px-2 md:px-4">
        <span class="sidebar-logo">🏠</span>
        <span class="sidebar-text hidden ml-2">MyApp</span>
    </div>

    <!-- Navigation -->
    <nav class="flex flex-col gap-4 px-5 mt-4">
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium">
            <span>🏠</span> <span class="sidebar-text hidden">Dashboard</span>
        </a>
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium">
            <span>👤</span> <span class="sidebar-text hidden">Profile</span>
        </a>
        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-black font-medium">
            <span>⚙️</span> <span class="sidebar-text hidden">Settings</span>
        </a>
        <a href="#" id="logoutBtn" class="flex items-center gap-3 text-red-500 hover:text-red-600 font-medium">
    <span>🚪</span> <span class="sidebar-text hidden">Logout</span>
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
