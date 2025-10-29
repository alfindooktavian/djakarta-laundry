const API_BASE = 'http://localhost:8000/api/payments'; // Ganti sesuai route API kamu

// Ambil semua payment
export async function fetchPayments() {
    try {
        const token = localStorage.getItem('api_token');

        const res = await fetch(API_BASE, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            throw new Error('Gagal mengambil data payments');
        }

        const data = await res.json();
        console.log('Data payments:', data);
        return data;
    } catch (err) {
        console.error('Error fetchPayments:', err);
        return [];
    }
}

// Ambil payment berdasarkan ID
export async function fetchPaymentById(id) {
    try {
        const token = localStorage.getItem('api_token');

        const res = await fetch(`${API_BASE}/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error('Payment tidak ditemukan');

        return await res.json();
    } catch (err) {
        console.error('Error fetchPaymentById:', err);
        return null;
    }
}

// Tambah payment baru
export async function createPayment(data) {
    try {
        const token = localStorage.getItem('api_token');

        const res = await fetch(API_BASE, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!res.ok) {
            const errData = await res.json();
            throw new Error(errData.message || 'Gagal menambahkan payment');
        }

        return await res.json();
    } catch (err) {
        console.error('Error createPayment:', err);
        return null;
    }
}

// Update data payment (misal ubah status jadi paid)
export async function updatePayment(id, data) {
    try {
        const token = localStorage.getItem('api_token');

        const res = await fetch(`${API_BASE}/${id}`, {
            method: 'PATCH', // Laravel pakai PATCH
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!res.ok) {
            const errData = await res.json();
            throw new Error(errData.message || 'Gagal memperbarui payment');
        }

        return await res.json();
    } catch (err) {
        console.error('Error updatePayment:', err);
        return null;
    }
}

// Hapus payment
export async function deletePayment(id) {
    try {
        const token = localStorage.getItem('api_token');

        const res = await fetch(`${API_BASE}/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            const errData = await res.json();
            throw new Error(errData.message || 'Gagal menghapus payment');
        }

        return await res.json();
    } catch (err) {
        console.error('Error deletePayment:', err);
        return null;
    }
}

export async function fetchPaymentByOrderId(orderId) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}?order_id=${orderId}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error('Payment tidak ditemukan untuk order ini');
        return await res.json();
    } catch (err) {
        console.error('Error fetchPaymentByOrderId:', err);
        return null;
    }
}


// ==== Expose ke global supaya bisa dipanggil di file Blade ====
window.fetchPayments = fetchPayments;
window.fetchPaymentById = fetchPaymentById;
window.createPayment = createPayment;
window.updatePayment = updatePayment;
window.deletePayment = deletePayment;
window.fetchPaymentByOrderId = fetchPaymentByOrderId;
