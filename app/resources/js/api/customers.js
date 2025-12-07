// resources/js/api/customers.js
import Swal from "sweetalert2";

const API_BASE = `${import.meta.env.VITE_API_BASE_URL}/customers`;

export async function fetchCustomers(page = 1, all = false) {
  try {
    const token = localStorage.getItem("api_token");
    const url = all ? `${API_BASE}?all=true` : `${API_BASE}?page=${page}`;

    const res = await fetch(url, {
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
    });

    if (!res.ok) throw new Error("Gagal mengambil data pelanggan");

    const data = await res.json();
    return all
      ? data
      : {
          customers: data.data,
          current_page: data.current_page,
          last_page: data.last_page,
          total: data.total,
          per_page: data.per_page,
        };
  } catch (err) {
    console.error("❌ Error fetchCustomers:", err);
    Swal.fire({
      icon: "error",
      title: "Gagal Memuat Data",
      text: "Tidak dapat mengambil daftar pelanggan. Coba lagi nanti.",
    });
    return all
      ? []
      : {
          customers: [],
          current_page: 1,
          last_page: 1,
          total: 0,
          per_page: 10,
        };
  }
}

export async function fetchCustomerById(id) {
  try {
    const token = localStorage.getItem("api_token");
    const res = await fetch(`${API_BASE}/${id}`, {
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
    });

    if (!res.ok) throw new Error("Customer tidak ditemukan");
    return await res.json();
  } catch (err) {
    console.error("❌ Error fetchCustomerById:", err);
    Swal.fire({
      icon: "error",
      title: "Data Tidak Ditemukan",
      text: "Pelanggan tidak tersedia atau sudah dihapus.",
    });
    return null;
  }
}

export async function createCustomer(data) {
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

    if (!res.ok) throw new Error(result.message || "Gagal menambahkan pelanggan");

    Swal.fire({
      icon: "success",
      title: "Berhasil!",
      text: "Pelanggan baru berhasil ditambahkan.",
      showConfirmButton: false,
      timer: 1500,
    });

    return result;
  } catch (err) {
    console.error("❌ Error createCustomer:", err);
    Swal.fire("Error", err.message || "Gagal menambahkan pelanggan", "error");
    return null;
  }
}

export async function updateCustomer(id, data) {
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

    if (!res.ok) throw new Error(result.message || "Gagal memperbarui pelanggan");

    Swal.fire({
      icon: "success",
      title: "Berhasil!",
      text: "Data pelanggan berhasil diperbarui.",
      showConfirmButton: false,
      timer: 1500,
    });

    return result;
  } catch (err) {
    console.error("❌ Error updateCustomer:", err);
    Swal.fire("Error", err.message || "Gagal memperbarui pelanggan", "error");
    return null;
  }
}

export async function deleteCustomer(id) {
  const token = localStorage.getItem("api_token");

  const result = await Swal.fire({
    title: "Yakin ingin menghapus pelanggan ini?",
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

    if (!res.ok) throw new Error(data.message || "Gagal menghapus pelanggan");

    Swal.fire({
      icon: "success",
      title: "Pelanggan Berhasil Dihapus",
      showConfirmButton: false,
      timer: 1500,
    });

    return data;
  } catch (err) {
    console.error("❌ Error deleteCustomer:", err);
    Swal.fire("Error", err.message || "Gagal menghapus pelanggan", "error");
    return null;
  }
}

window.fetchCustomers = fetchCustomers;
window.fetchCustomerById = fetchCustomerById;
window.createCustomer = createCustomer;
window.updateCustomer = updateCustomer;
window.deleteCustomer = deleteCustomer;
