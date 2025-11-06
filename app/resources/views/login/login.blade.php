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

    <div class="bg-white shadow-lg rounded-2xl w-full max-w-md p-6 sm:p-8 border border-blue-100">
        <h2 class="text-center text-xl sm:text-2xl font-semibold text-gray-800 mb-1">Masuk ke Akun Anda</h2>
        <p class="text-center text-gray-500 text-sm mb-6">Masukkan email dan kata sandi untuk melanjutkan</p>

        <div id="error"
            class="hidden text-red-600 text-center bg-red-50 border border-red-200 rounded-md py-2 px-3 text-sm mb-4">
        </div>

        <form id="loginForm" class="flex flex-col gap-4">
            <div>
                <label for="email" class="block text-gray-700 text-sm font-medium mb-1">Email</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:outline-none text-sm"
                    required>
            </div>

            <div>
                <label for="password" class="block text-gray-700 text-sm font-medium mb-1">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:outline-none text-sm"
                    required>
            </div>

            <button id="loginBtn" type="submit"
                class="w-full bg-black text-white py-2 rounded-lg font-medium hover:bg-gray-800 transition text-sm sm:text-base flex justify-center items-center gap-2">
                <span id="loginText">Masuk</span>
                <svg id="spinner" class="hidden animate-spin h-4 w-4 text-white"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
            </button>
        </form>
    </div>

    <script type="module">
        if (localStorage.getItem('api_token')) {
            window.location.href = "/dashboard";
        }

        const form = document.getElementById('loginForm');
        const errorElement = document.getElementById('error');
        const loginBtn = document.getElementById('loginBtn');
        const spinner = document.getElementById('spinner');
        const loginText = document.getElementById('loginText');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!email || !password) return;

            // tampilkan loading
            spinner.classList.remove('hidden');
            loginText.textContent = 'Memproses...';
            loginBtn.disabled = true;

            const result = await window.loginUser(email, password);

            // sembunyikan loading
            spinner.classList.add('hidden');
            loginText.textContent = 'Masuk';
            loginBtn.disabled = false;

            if (result.error) {
                errorElement.textContent = result.error;
                errorElement.classList.remove('hidden');
                setTimeout(() => errorElement.classList.add('hidden'), 4000);
            } else {
                localStorage.setItem('api_token', result.token);
                window.location.href = "/dashboard";
            }
        });
    </script>

</body>
</html>
