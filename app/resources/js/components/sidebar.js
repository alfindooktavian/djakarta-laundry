import { logoutUser } from "../api/auth.js";

document.addEventListener("DOMContentLoaded", () => {
    const userData = localStorage.getItem("user");
    let userRole = "";

    if (userData) {
        try {
            const user = JSON.parse(userData);
            document.getElementById("userName").textContent = user.name || "User";
            document.getElementById("userRole").textContent = user.role || "Tidak diketahui";
            userRole = user.role || "";
        } catch (e) {
            console.error("Error parsing user data:", e);
        }
    } else {
        window.location.href = "/login";
    }

    const menuRules = [
        { route: "/dashboard", roles: ["superadmin", "owner", "karyawan"] },
        { route: "/administrator", roles: ["superadmin"] },
        { route: "/service", roles: ["superadmin", "owner"] },
        { route: "/customer", roles: ["superadmin", "owner", "karyawan"] },
        { route: "/order", roles: ["superadmin", "owner", "karyawan"] },
        { route: "/chat", roles: ["superadmin", "owner"] },
        { route: "/report", roles: ["superadmin", "owner"] },
    ];

    menuRules.forEach(item => {
        const link = document.querySelector(`a[href='${item.route}']`);
        if (link && !item.roles.includes(userRole)) {
            link.style.display = "none";
        }
    });

    const logoutBtn = document.getElementById("logoutBtn");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", async () => {
            const confirmed = confirm("Apakah kamu yakin ingin logout?");
            if (!confirmed) return;

            const res = await logoutUser();
            if (res && !res.error) {
                window.location.href = "/login";
            } else {
                alert(res.error || "Logout gagal, silakan coba lagi.");
            }
        });
    }
});
