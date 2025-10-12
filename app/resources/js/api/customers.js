const API_BASE = 'http://localhost:8000/api/customers'; // Sesuaikan dengan route API kamu

// Ambil semua customer
export async function fetchCustomers() {
    try {
        const token = localStorage.getItem('api_token');

        const res = await fetch(API_BASE, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            throw new Error('Gagal mengambil data customers');
        }

        const data = await res.json();
        console.log('Data customers:', data);
        return data;
    } catch (err) {
        console.error('Error fetchCustomers:', err);
        return [];
    }
}

// Ambil customer berdasarkan ID
export async function fetchCustomerById(id) {
    try {
        const token = localStorage.getItem('api_token');

        const res = await fetch(`${API_BASE}/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error('Customer tidak ditemukan');

        return await res.json();
    } catch (err) {
        console.error('Error fetchCustomerById:', err);
        return null;
    }
}

// Tambah customer baru
export async function createCustomer(data) {
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
            throw new Error(errData.message || 'Gagal menambahkan customer');
        }

        return await res.json();
    } catch (err) {
        console.error('Error createCustomer:', err);
        return null;
    }
}

// Update data customer
export async function updateCustomer(id, data) {
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
            throw new Error(errData.message || 'Gagal memperbarui customer');
        }

        return await res.json();
    } catch (err) {
        console.error('Error updateCustomer:', err);
        return null;
    }
}

// Hapus customer
export async function deleteCustomer(id) {
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
            throw new Error(errData.message || 'Gagal menghapus customer');
        }

        return await res.json();
    } catch (err) {
        console.error('Error deleteCustomer:', err);
        return null;
    }
}

// ==== Expose ke global supaya bisa dipanggil di file Blade ====
window.fetchCustomers = fetchCustomers;
window.fetchCustomerById = fetchCustomerById;
window.createCustomer = createCustomer;
window.updateCustomer = updateCustomer;
window.deleteCustomer = deleteCustomer;
