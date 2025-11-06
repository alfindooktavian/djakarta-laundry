// resources/js/api/order-details.js

const API_BASE = `${import.meta.env.VITE_API_BASE_URL}/order-details`;

export async function fetchOrderDetails() {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(API_BASE, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
        });

        if (!res.ok) throw new Error('Gagal mengambil data order detail');

        const data = await res.json();
        return data;
    } catch (err) {
        console.error('Error fetchOrderDetails:', err);
        return [];
    }
}

export async function fetchOrderDetailById(id) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
        });

        if (!res.ok) throw new Error('Order detail tidak ditemukan');
        return await res.json();
    } catch (err) {
        console.error('Error fetchOrderDetailById:', err);
        return null;
    }
}

export async function createOrderDetail(data) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(API_BASE, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
            body: JSON.stringify(data),
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

export async function updateOrderDetail(id, data) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
            body: JSON.stringify(data),
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

export async function deleteOrderDetail(id) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
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

window.fetchOrderDetails = fetchOrderDetails;
window.fetchOrderDetailById = fetchOrderDetailById;
window.createOrderDetail = createOrderDetail;
window.updateOrderDetail = updateOrderDetail;
window.deleteOrderDetail = deleteOrderDetail;
