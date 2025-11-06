// resources/js/api/payments.js

const API_BASE = `${import.meta.env.VITE_API_BASE_URL}/payments`;

export async function fetchPayments() {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(API_BASE, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
        });

        if (!res.ok) throw new Error('Gagal mengambil data payments');
        const data = await res.json();
        return data;
    } catch (err) {
        console.error('Error fetchPayments:', err);
        return [];
    }
}

export async function fetchPaymentById(id) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            },
        });

        if (!res.ok) throw new Error('Payment tidak ditemukan');
        return await res.json();
    } catch (err) {
        console.error('Error fetchPaymentById:', err);
        return null;
    }
}

export async function createPayment(data) {
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
            throw new Error(errData.message || 'Gagal menambahkan payment');
        }

        return await res.json();
    } catch (err) {
        console.error('Error createPayment:', err);
        return null;
    }
}

export async function updatePayment(id, data) {
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
            throw new Error(errData.message || 'Gagal memperbarui payment');
        }

        return await res.json();
    } catch (err) {
        console.error('Error updatePayment:', err);
        return null;
    }
}

export async function deletePayment(id) {
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
                'Accept': 'application/json',
            },
        });

        if (!res.ok) throw new Error('Payment tidak ditemukan untuk order ini');
        return await res.json();
    } catch (err) {
        console.error('Error fetchPaymentByOrderId:', err);
        return null;
    }
}

window.fetchPayments = fetchPayments;
window.fetchPaymentById = fetchPaymentById;
window.createPayment = createPayment;
window.updatePayment = updatePayment;
window.deletePayment = deletePayment;
window.fetchPaymentByOrderId = fetchPaymentByOrderId;
