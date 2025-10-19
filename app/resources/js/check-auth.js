// resources/js/checkAuth.js

const token = localStorage.getItem("api_token");

// Jika tidak ada token, redirect ke halaman login
if (!token) {
    window.location.href = "/login";
}
