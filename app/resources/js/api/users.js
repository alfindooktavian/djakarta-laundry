import Swal from "sweetalert2";

const API_BASE = `${import.meta.env.VITE_API_BASE_URL}/users`;

export async function fetchUsers(page = 1, all = false) {
  try {
    const token = localStorage.getItem("api_token");
    const url = all ? `${API_BASE}?all=true` : `${API_BASE}?page=${page}`;
    const res = await fetch(url, {
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
    });

    if (!res.ok) throw new Error("Gagal mengambil data pengguna");

    const data = await res.json();
    return all
      ? data
      : {
          users: data.data,
          current_page: data.current_page,
          last_page: data.last_page,
          total: data.total,
          per_page: data.per_page,
        };
  } catch (err) {
    console.error("Error fetchUsers:", err);
    Swal.fire({
      icon: "error",
      title: "Gagal Memuat Data",
      text: "Tidak dapat mengambil daftar pengguna. Silakan coba lagi nanti.",
    });
    return all
      ? []
      : {
          users: [],
          current_page: 1,
          last_page: 1,
          total: 0,
          per_page: 10,
        };
  }
}

export async function fetchUserById(id) {
  try {
    const token = localStorage.getItem("api_token");
    const res = await fetch(`${API_BASE}/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
    });

    if (!res.ok) throw new Error("Pengguna tidak ditemukan");
    return await res.json();
  } catch (err) {
    console.error("Error fetchUserById:", err);
    Swal.fire({
      icon: "error",
      title: "Pengguna Tidak Ditemukan",
      text: "Data pengguna tidak tersedia atau sudah dihapus.",
    });
    return null;
  }
}

export async function createUser(data) {
  const token = localStorage.getItem("api_token");
  try {
    Swal.fire({
      title: "Menyimpan...",
      text: "Mohon tunggu sebentar.",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
    });

    const res = await fetch(API_BASE, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify(data),
    });

    const result = await res.json();
    if (!res.ok) throw new Error(result.message || "Gagal membuat pengguna");

    Swal.fire({
      icon: "success",
      title: "Berhasil!",
      text: "Pengguna baru berhasil ditambahkan.",
      showConfirmButton: false,
      timer: 1500,
    });

    return result;
  } catch (err) {
    console.error("Error createUser:", err);
    Swal.fire("Error", err.message || "Gagal menambahkan pengguna", "error");
    return null;
  }
}

export async function updateUser(id, data) {
  const token = localStorage.getItem("api_token");
  try {
    Swal.fire({
      title: "Memperbarui...",
      text: "Mohon tunggu sebentar.",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
    });

    const res = await fetch(`${API_BASE}/${id}`, {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify(data),
    });

    const result = await res.json();
    if (!res.ok) throw new Error(result.message || "Gagal memperbarui pengguna");

    Swal.fire({
      icon: "success",
      title: "Berhasil!",
      text: "Data pengguna berhasil diperbarui.",
      showConfirmButton: false,
      timer: 1500,
    });

    return result;
  } catch (err) {
    console.error("Error updateUser:", err);
    Swal.fire("Error", err.message || "Gagal memperbarui pengguna", "error");
    return null;
  }
}

export async function deleteUser(id) {
  const token = localStorage.getItem("api_token");

  const result = await Swal.fire({
    title: "Yakin ingin menghapus pengguna ini?",
    text: "Tindakan ini tidak dapat dibatalkan.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#2563eb",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya, hapus",
    cancelButtonText: "Batal",
  });

  if (!result.isConfirmed) return { cancelled: true };

  try {
    Swal.fire({
      title: "Menghapus...",
      text: "Mohon tunggu sebentar.",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
    });

    const res = await fetch(`${API_BASE}/${id}`, {
      method: "DELETE",
      headers: { Authorization: `Bearer ${token}` },
    });

    const data = await res.json();
    if (!res.ok) throw new Error(data.message || "Gagal menghapus pengguna");

    Swal.fire({
      icon: "success",
      title: "Pengguna Berhasil Dihapus",
      showConfirmButton: false,
      timer: 1500,
    });

    return data;
  } catch (err) {
    console.error("Error deleteUser:", err);
    Swal.fire("Error", err.message || "Gagal menghapus pengguna", "error");
    return null;
  }
}

window.fetchUsers = fetchUsers;
window.fetchUserById = fetchUserById;
window.createUser = createUser;
window.updateUser = updateUser;
window.deleteUser = deleteUser;
