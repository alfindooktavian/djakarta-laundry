// resources/js/api/orders.js

const API_BASE = `${import.meta.env.VITE_API_BASE_URL}/orders`;

export async function fetchOrders(page = 1, all = false) {
    try {
        const token = localStorage.getItem('api_token');
        const url = all ? `${API_BASE}?all=true` : `${API_BASE}?page=${page}`;

        const res = await fetch(url, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
        });

        if (!res.ok) throw new Error('Gagal mengambil data orders');

        const data = await res.json();
        return all
            ? data
            : {
                  orders: data.data,
                  current_page: data.current_page,
                  last_page: data.last_page,
                  total: data.total,
                  per_page: data.per_page,
              };
    } catch (err) {
        console.error('Error fetchOrders:', err);
        return all
            ? []
            : {
                  orders: [],
                  current_page: 1,
                  last_page: 1,
                  total: 0,
                  per_page: 10,
              };
    }
}

export async function fetchOrderById(id) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
        });

        if (!res.ok) throw new Error('Order tidak ditemukan');
        return await res.json();
    } catch (err) {
        console.error('Error fetchOrderById:', err);
        return null;
    }
}

export async function createOrder(data) {
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
            throw new Error(errData.message || 'Gagal menambahkan order');
        }

        return await res.json();
    } catch (err) {
        console.error('Error createOrder:', err);
        return null;
    }
}

export async function updateOrder(id, data) {
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
            throw new Error(errData.message || 'Gagal memperbarui order');
        }

        return await res.json();
    } catch (err) {
        console.error('Error updateOrder:', err);
        return null;
    }
}

export async function deleteOrder(id) {
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
            throw new Error(errData.message || 'Gagal menghapus order');
        }

        return await res.json();
    } catch (err) {
        console.error('Error deleteOrder:', err);
        return null;
    }
}

window.fetchOrders = fetchOrders;
window.fetchOrderById = fetchOrderById;
window.createOrder = createOrder;
window.updateOrder = updateOrder;
window.deleteOrder = deleteOrder;
