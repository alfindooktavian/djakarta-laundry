const API_BASE = 'http://localhost:8000/api/orders'; // Sesuaikan dengan route API kamu

// Ambil semua order
export async function fetchOrders() {
    try {
        const token = localStorage.getItem('api_token');

        const res = await fetch(API_BASE, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            throw new Error('Gagal mengambil data orders');
        }

        const data = await res.json();
        console.log('Data orders:', data);
        return data;
    } catch (err) {
        console.error('Error fetchOrders:', err);
        return [];
    }
}

// Ambil order berdasarkan ID
export async function fetchOrderById(id) {
    try {
        const token = localStorage.getItem('api_token');

        const res = await fetch(`${API_BASE}/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error('Order tidak ditemukan');

        return await res.json();
    } catch (err) {
        console.error('Error fetchOrderById:', err);
        return null;
    }
}

// Tambah order baru
export async function createOrder(data) {
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
            throw new Error(errData.message || 'Gagal menambahkan order');
        }

        return await res.json();
    } catch (err) {
        console.error('Error createOrder:', err);
        return null;
    }
}

// Update data order
export async function updateOrder(id, data) {
    try {
        const token = localStorage.getItem('api_token');

        const res = await fetch(`${API_BASE}/${id}`, {
            method: 'PATCH', // Laravel biasanya pakai PATCH
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!res.ok) {
            const errData = await res.json();
            throw new Error(errData.message || 'Gagal memperbarui order');
        }

        return await res.json();
    } catch (err) {
        console.error('Error updateOrder:', err);
        return null;
    }
}

// Hapus order
export async function deleteOrder(id) {
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
            throw new Error(errData.message || 'Gagal menghapus order');
        }

        return await res.json();
    } catch (err) {
        console.error('Error deleteOrder:', err);
        return null;
    }
}

// ==== Expose ke global supaya bisa dipanggil dari Blade ====
window.fetchOrders = fetchOrders;
window.fetchOrderById = fetchOrderById;
window.createOrder = createOrder;
window.updateOrder = updateOrder;
window.deleteOrder = deleteOrder;
