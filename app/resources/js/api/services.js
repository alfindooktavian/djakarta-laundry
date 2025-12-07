import Swal from "sweetalert2";

const API_BASE = `${import.meta.env.VITE_API_BASE_URL}/services`;

export async function fetchServices(page = 1, all = false) {
  try {
    const token = localStorage.getItem("api_token");
    const url = all ? `${API_BASE}?all=true` : `${API_BASE}?page=${page}`;
    const res = await fetch(url, {
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
    });

    if (!res.ok) throw new Error("Gagal mengambil data layanan");

    const data = await res.json();
    return all
      ? data
      : {
          services: data.data,
          current_page: data.current_page,
          last_page: data.last_page,
          total: data.total,
          per_page: data.per_page,
        };
  } catch (err) {
    console.error("Error fetchServices:", err);
    Swal.fire({
      icon: "error",
      title: "Gagal Memuat Data",
      text: "Tidak dapat mengambil daftar layanan. Coba lagi nanti.",
    });
    return all
      ? []
      : {
          services: [],
          current_page: 1,
          last_page: 1,
          total: 0,
          per_page: 10,
        };
  }
}

export async function fetchServiceById(id) {
  try {
    const token = localStorage.getItem("api_token");
    const res = await fetch(`${API_BASE}/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
    });

    if (!res.ok) throw new Error("Service tidak ditemukan");
    return await res.json();
  } catch (err) {
    console.error("Error fetchServiceById:", err);
    Swal.fire({
      icon: "error",
      title: "Layanan Tidak Ditemukan",
      text: "Layanan ini tidak tersedia atau sudah dihapus.",
    });
    return null;
  }
}

export async function createService(data) {
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

    if (!res.ok) throw new Error(result.message || "Gagal membuat layanan");

    Swal.fire({
      icon: "success",
      title: "Berhasil!",
      text: "Layanan baru berhasil ditambahkan.",
      showConfirmButton: false,
      timer: 1500,
    });

    return result;
  } catch (err) {
    console.error("Error createService:", err);
    Swal.fire("Error", err.message || "Gagal menambahkan layanan", "error");
    return null;
  }
}

export async function updateService(id, data) {
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

    if (!res.ok) throw new Error(result.message || "Gagal memperbarui layanan");

    Swal.fire({
      icon: "success",
      title: "Berhasil!",
      text: "Data layanan berhasil diperbarui.",
      showConfirmButton: false,
      timer: 1500,
    });

    return result;
  } catch (err) {
    console.error("Error updateService:", err);
    Swal.fire("Error", err.message || "Gagal memperbarui layanan", "error");
    return null;
  }
}

export async function deleteService(id) {
  const token = localStorage.getItem("api_token");

  const result = await Swal.fire({
    title: "Yakin ingin menghapus layanan ini?",
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

    if (!res.ok) throw new Error(data.message || "Gagal menghapus layanan");

    Swal.fire({
      icon: "success",
      title: "Layanan Berhasil Dihapus",
      showConfirmButton: false,
      timer: 1500,
    });

    return data;
  } catch (err) {
    console.error("Error deleteService:", err);
    Swal.fire("Error", err.message || "Gagal menghapus layanan", "error");
    return null;
  }
}

window.fetchServices = fetchServices;
window.fetchServiceById = fetchServiceById;
window.createService = createService;
window.updateService = updateService;
window.deleteService = deleteService;
