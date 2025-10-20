const API_BASE = 'http://localhost:8000/api/order-details'; // Sesuaikan dengan route API kamu

// Ambil semua order detail
export async function fetchOrderDetails() {
    try {
        const token = localStorage.getItem('api_token');

        const res = await fetch(API_BASE, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error('Gagal mengambil data order detail');

        const data = await res.json();
        console.log('Data order details:', data);
        return data;
    } catch (err) {
        console.error('Error fetchOrderDetails:', err);
        return [];
    }
}

// Ambil order detail berdasarkan ID
export async function fetchOrderDetailById(id) {
    try {
        const token = localStorage.getItem('api_token');

        const res = await fetch(`${API_BASE}/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error('Order detail tidak ditemukan');

        return await res.json();
    } catch (err) {
        console.error('Error fetchOrderDetailById:', err);
        return null;
    }
}

// Tambah order detail baru
export async function createOrderDetail(data) {
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
            throw new Error(errData.message || 'Gagal menambahkan order detail');
        }

        return await res.json();
    } catch (err) {
        console.error('Error createOrderDetail:', err);
        return null;
    }
}

// Update order detail
export async function updateOrderDetail(id, data) {
    try {
        const token = localStorage.getItem('api_token');

        const res = await fetch(`${API_BASE}/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!res.ok) {
            const errData = await res.json();
            throw new Error(errData.message || 'Gagal memperbarui order detail');
        }

        return await res.json();
    } catch (err) {
        console.error('Error updateOrderDetail:', err);
        return null;
    }
}

// Hapus order detail
export async function deleteOrderDetail(id) {
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
            throw new Error(errData.message || 'Gagal menghapus order detail');
        }

        return await res.json();
    } catch (err) {
        console.error('Error deleteOrderDetail:', err);
        return null;
    }
}

// ==== Expose ke global supaya bisa dipanggil dari Blade ====
window.fetchOrderDetails = fetchOrderDetails;
window.fetchOrderDetailById = fetchOrderDetailById;
window.createOrderDetail = createOrderDetail;
window.updateOrderDetail = updateOrderDetail;
window.deleteOrderDetail = deleteOrderDetail;
