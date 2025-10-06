@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div id="userInfo" class="mb-6 text-gray-800"></div>

    <script>
        // Ambil data user dari localStorage
        const user = JSON.parse(localStorage.getItem("user"));
        const token = localStorage.getItem("api_token");

        if (!user || !token) {
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
            window.location.href = "/login";
        });
    </script>
@endsection
