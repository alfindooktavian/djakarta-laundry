const API_BASE = import.meta.env.VITE_API_BASE_URL;

export async function loginUser(email, password) {
  try {
    const res = await fetch(`${API_BASE}/login`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ email, password }),
    });

    const data = await res.json();

    if (!res.ok) {
      throw new Error(data.message || "Login gagal, periksa kembali kredensial Anda.");
    }

    if (data.token && data.user) {
      localStorage.setItem("api_token", data.token);
      localStorage.setItem("user", JSON.stringify(data.user));
    }

    return data;
  } catch (err) {
    console.error("Error loginUser:", err);
    return { error: err.message };
  }
}

export async function logoutUser() {
  const token = localStorage.getItem("api_token");

  try {
    if (token) {
      const res = await fetch(`${API_BASE}/logout`, {
        method: "POST",
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json",
        },
      });

      const data = await res.json();
      return res.ok ? data : { error: data.message || "Logout gagal" };
    }
  } catch (err) {
    console.error("Error logoutUser:", err);
    return { error: "Terjadi kesalahan saat logout." };
  }

  return { message: "Logout berhasil" };
}

window.loginUser = loginUser;
window.logoutUser = logoutUser;
