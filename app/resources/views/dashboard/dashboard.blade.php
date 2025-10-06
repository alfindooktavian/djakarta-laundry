<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>

    <h2>Dashboard</h2>

    <div id="userInfo"></div>

    <br>
    <button id="logoutBtn">Logout</button>

    <script>
        // Ambil data user dari localStorage
        const user = JSON.parse(localStorage.getItem("user"));
        const token = localStorage.getItem("api_token");

        if (!user || !token) {
            // kalau belum login, redirect ke login
            window.location.href = "/login";
        } else {
            document.getElementById("userInfo").innerHTML = `
                <p>Selamat datang, <b>${user.name}</b></p>
                <p>Email: ${user.email}</p>
                <p>Token: ${token}</p>
            `;
        }

        // Tombol logout
        document.getElementById("logoutBtn").addEventListener("click", () => {
            localStorage.removeItem("api_token");
            localStorage.removeItem("user");
            window.location.href = "/";
        });
    </script>

</body>
</html>
