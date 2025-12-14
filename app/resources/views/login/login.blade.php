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
    <!-- ================= MODAL KIRIM KODE ================= -->
    <div id="forgotModal" class="fixed inset-0 hidden items-center justify-center z-50 pointer-events-none">
    <div class="bg-white shadow-lg rounded-2xl w-full max-w-md min-h-[390px]
            p-6 sm:p-8 border border-blue-100 pointer-events-auto
            flex flex-col justify-center">


        <h3 class="text-center text-xl font-semibold text-gray-800 mb-2">Reset Password</h3>
        <p class="text-center text-gray-500 text-sm mb-5">Masukkan email untuk menerima kode</p>

        <div id="forgotError"
            class="hidden text-red-600 text-center bg-red-50 border border-red-200 rounded-md py-2 px-3 text-sm mb-4">
        </div>

        <form id="forgotForm" class="flex flex-col gap-4">
            <input type="email" id="forgotEmail" placeholder="Masukkan email Anda"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:outline-none text-sm"
                required>

            <button type="submit"
                class="w-full bg-black text-white py-2 rounded-lg font-medium hover:bg-gray-800 transition text-sm">
                Kirim Kode
            </button>

            <button type="button" id="closeForgot"
                class="text-sm text-gray-500 hover:underline text-center">
                Batal
            </button>
        </form>
    </div>
</div>


<!-- ================= MODAL RESET PASSWORD ================= -->
<div id="resetModal" class="fixed inset-0 hidden items-center justify-center z-50 pointer-events-none">
    <div class="bg-white shadow-lg rounded-2xl w-full max-w-md p-6 sm:p-8 border border-blue-100 pointer-events-auto">
        <h3 class="text-center text-xl font-semibold text-gray-800 mb-2">Password Baru</h3>
        <p class="text-center text-gray-500 text-sm mb-5">Masukkan kode & password baru</p>

        <div id="resetError"
            class="hidden text-red-600 text-center bg-red-50 border border-red-200 rounded-md py-2 px-3 text-sm mb-4">
        </div>

        <form id="resetForm" class="flex flex-col gap-4">
            <input type="email" id="resetEmail" placeholder="Email"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" required>

            <input type="text" id="resetCode" placeholder="Kode 6 digit dari email"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" required>

            <input type="password" id="newPassword" placeholder="Password baru"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" required>

            <input type="password" id="confirmPassword" placeholder="Konfirmasi password"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" required>

            <button type="submit"
                class="w-full bg-black text-white py-2 rounded-lg font-medium hover:bg-gray-800 transition text-sm">
                Reset Password
            </button>

            <button type="button" id="closeReset"
                class="text-sm text-gray-500 hover:underline text-center">
                Batal
            </button>
        </form>
    </div>
</div>


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
            <div class="text-right mt-2">
    <button type="button" id="forgotBtn" class="text-blue-600 text-sm hover:underline">
        Lupa Password?
    </button>
</div>

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

        const forgotBtn = document.getElementById('forgotBtn');
const forgotModal = document.getElementById('forgotModal');
const closeForgot = document.getElementById('closeForgot');
const forgotForm = document.getElementById('forgotForm');
const forgotError = document.getElementById('forgotError');

const resetModal = document.getElementById('resetModal');
const resetForm = document.getElementById('resetForm');
const resetError = document.getElementById('resetError');
const closeReset = document.getElementById('closeReset');

// ================= OPEN MODAL KIRIM KODE =================
forgotBtn.addEventListener('click', () => {
    forgotModal.classList.remove('hidden');
    forgotModal.classList.add('flex');
});

// ================= CLOSE MODAL KIRIM KODE =================
closeForgot.addEventListener('click', () => {
    forgotModal.classList.add('hidden');
    forgotModal.classList.remove('flex');
});

// ================= KIRIM KODE KE EMAIL (FETCH) =================
forgotForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.getElementById('forgotEmail').value.trim();
    if (!email) return;

    try {
        const res = await fetch('/api/request-reset-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email })
        });

        const data = await res.json();
        if (!res.ok) throw data;

        alert(data.message + "\nSilakan cek email Anda.");

        forgotModal.classList.add('hidden');
        forgotModal.classList.remove('flex');

        resetModal.classList.remove('hidden');
        resetModal.classList.add('flex');

        document.getElementById('resetEmail').value = email;

    } catch (err) {
        forgotError.textContent = err.message || 'Gagal mengirim kode';
        forgotError.classList.remove('hidden');

        setTimeout(() => forgotError.classList.add('hidden'), 4000);
    }
});

// ================= CLOSE MODAL RESET =================
closeReset.addEventListener('click', () => {
    resetModal.classList.add('hidden');
    resetModal.classList.remove('flex');
});

// ================= SUBMIT RESET PASSWORD (FETCH) =================
resetForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.getElementById('resetEmail').value.trim();
    const code = document.getElementById('resetCode').value.trim();
    const password = document.getElementById('newPassword').value.trim();
    const password_confirmation = document.getElementById('confirmPassword').value.trim();

    if (!email || !code || !password || !password_confirmation) return;

    try {
        const res = await fetch('/api/reset-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email,
                code,
                password,
                password_confirmation
            })
        });

        const data = await res.json();
        if (!res.ok) throw data;

        alert(data.message + "\nSilakan login kembali.");

        resetModal.classList.add('hidden');
        resetModal.classList.remove('flex');

    } catch (err) {
        resetError.textContent = err.message || 'Reset password gagal';
        resetError.classList.remove('hidden');

        setTimeout(() => resetError.classList.add('hidden'), 4000);
    }
});


    </script>

</body>
</html>
