<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  
    @vite('resources/js/api/auth.js')
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen px-4">

    <div class="flex flex-col items-center w-full max-w-[400px] gap-2">
        <!-- Title -->
        <h1 class="w-full sm:w-[209px] h-[36px] text-[24px] leading-[150%] font-[600] text-center tracking-[-0.01em]" style="font-family: 'Inter', sans-serif;">
            Create an account
        </h1>

        <!-- Subtitle -->
        <p class="text-[16px] font-normal text-center w-full sm:w-[321px]" style="font-family: 'Inter', sans-serif;">
            Enter your username to sign in for this app
        </p>

        <!-- Error Message -->
        <div id="error" class="text-red-500 text-center mb-2"></div>

        <!-- Form -->
        <form id="loginForm" class="flex flex-col gap-4 w-full">
            <!-- Email Field -->
            <div class="flex items-center w-full h-[40px] bg-transparent border border-gray-300 rounded-[8px] px-4">
                <input type="email" id="email" name="email" placeholder="Email"
                       class="flex-grow h-full text-[20px] font-[500] placeholder-[#828282] bg-transparent outline-none"
                       style="font-family: 'Inter', sans-serif;" required>
            </div>

            <!-- Password Field -->
            <div class="flex items-center w-full h-[40px] bg-transparent border border-gray-300 rounded-[8px] px-4">
                <input type="password" id="password" name="password" placeholder="Password"
                       class="flex-grow h-full text-[20px] font-[500] placeholder-[#828282] bg-transparent outline-none"
                       style="font-family: 'Inter', sans-serif;" required>
            </div>

            <!-- Sign In Button -->
            <button type="submit"
                    class="w-full h-[40px] bg-black rounded-[8px] flex items-center justify-center text-white text-[16px] font-medium">
                Sign In
            </button>
        </form>
    </div>

    <script type="module">
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
            } else {
                // Redirect ke dashboard
                window.location.href = "/dashboard";
            }
        });
    </script>
</body>
</html>
