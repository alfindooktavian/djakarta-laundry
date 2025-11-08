import { logoutUser } from "../api/auth.js";
import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", () => {
  const userData = localStorage.getItem("user");
  let userRole = "";

  if (userData) {
    try {
      const user = JSON.parse(userData);
      document.getElementById("userName").textContent = user.name || "User";
      document.getElementById("userRole").textContent = user.role || "Tidak diketahui";
      userRole = user.role || "";

      const initial = user.name ? user.name.charAt(0).toUpperCase() : "U";
      const initialEl = document.getElementById("userInitial");
      if (initialEl) initialEl.textContent = initial;
    } catch {
      window.location.href = "/login";
      return;
    }
  } else {
    window.location.href = "/login";
    return;
  }

  const menuRules = [
    { route: "/dashboard", roles: ["superadmin", "owner", "karyawan"] },
    { route: "/administrator", roles: ["superadmin"] },
    { route: "/service", roles: ["superadmin", "owner"] },
    { route: "/customer", roles: ["superadmin", "owner", "karyawan"] },
    { route: "/order", roles: ["superadmin", "owner", "karyawan"] },
    { route: "/chat", roles: ["superadmin", "owner", "karyawan"] },
    { route: "/report", roles: ["superadmin", "owner"] },
  ];

  const allLinks = document.querySelectorAll("nav a");
  allLinks.forEach(link => {
    const href = link.getAttribute("href");
    const rule = menuRules.find(item => href.includes(item.route));
    if (rule && !rule.roles.includes(userRole)) link.classList.add("hidden");
  });

  const logoutBtn = document.getElementById("logoutBtn");
  const logoutBtnMobile = document.getElementById("logoutBtnMobile");

  const handleLogout = async (e) => {
    e.preventDefault();

    const result = await Swal.fire({
      title: "Yakin mau keluar?",
      text: "Sesi kamu akan berakhir.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#2563eb",
      cancelButtonColor: "#d33",
      confirmButtonText: "Ya, keluar",
      cancelButtonText: "Batal",
    });

    if (!result.isConfirmed) return;

    Swal.fire({
      title: "Keluar...",
      text: "Mohon tunggu sebentar.",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
    });

    try {
      const res = await logoutUser();

      if (!res.error) {
        localStorage.removeItem("user");
        localStorage.removeItem("api_token");

        Swal.fire({
          icon: "success",
          title: "Berhasil Logout",
          text: "Sampai jumpa lagi.",
          showConfirmButton: false,
          timer: 1500,
        });

        setTimeout(() => {
          window.location.href = "/login";
        }, 1500);
      } else {
        Swal.fire("Gagal", res.error || "Logout gagal, silakan coba lagi.", "error");
      }
    } catch {
      Swal.fire("Error", "Terjadi kesalahan saat logout.", "error");
    }
  };

  if (logoutBtn) logoutBtn.addEventListener("click", handleLogout);
  if (logoutBtnMobile) logoutBtnMobile.addEventListener("click", handleLogout);

  const currentPath = window.location.pathname;
  allLinks.forEach(link => {
    if (link.href.includes(currentPath)) {
      link.classList.add("bg-blue-100", "text-blue-600", "font-medium");
    }
  });
});
