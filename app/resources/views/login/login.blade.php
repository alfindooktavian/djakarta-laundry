<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Djakarta Laundry | Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    @vite('resources/js/api/auth.js')
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen px-4">

    <div class="bg-white shadow-lg rounded-2xl w-full max-w-md p-8 z-10 border border-blue-100">

        <!-- Judul -->
        <h2 class="text-center text-xl font-semibold text-gray-800 mb-1">Masuk ke Akun Anda</h2>
        <p class="text-center text-gray-500 text-sm mb-6">Masukkan email dan kata sandi untuk melanjutkan</p>

        <!-- Error Message -->
        <div id="error"
            class="hidden text-red-500 text-center bg-red-50 border border-red-200 rounded-md py-2 px-3 text-sm"></div>

        <!-- Form -->
        <form id="loginForm" class="flex flex-col gap-4">
            <!-- Email Field -->
            <div>
                <label for="email" class="block text-gray-700 text-sm font-medium mb-1">Email</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:outline-none text-sm"
                    required>
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-gray-700 text-sm font-medium mb-1">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:outline-none text-sm"
                    required>
            </div>

            <!-- Sign In Button -->
            <button type="submit"
                class="w-full bg-black text-white py-2 rounded-lg font-medium hover:bg-gray-800 transition">
                Masuk
            </button>
        </form>
    </div>

    <!-- Script -->
    <script type="module">
        // Cek api_token di localStorage saat halaman load
        if (localStorage.getItem('api_token')) {
            window.location.href = "/dashboard"; // langsung lempar ke dashboard
        }

        const form = document.getElementById('loginForm');
        const errorElement = document.getElementById('error');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Panggil fungsi dari auth.js
            const result = await window.loginUser(email, password);

            if (result.error) {
                errorElement.innerText = result.error;
                errorElement.classList.remove('hidden');
            } else {
                errorElement.classList.add('hidden');
                localStorage.setItem('api_token', result.token); // simpan api_token
                window.location.href = "/dashboard";
            }
        });
    </script>
</body>

</html>
