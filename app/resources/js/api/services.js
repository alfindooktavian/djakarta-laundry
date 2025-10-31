const API_BASE = 'http://localhost:8000/api/services'; // URL API Laravel

// Ambil semua service
export async function fetchServices(page = 1, all = false) {
    try {
        const token = localStorage.getItem('api_token');
        console.log('Token dari localStorage:', token);

        // Jika all = true, kirim parameter ?all=true ke API
        const url = all ? `${API_BASE}?all=true` : `${API_BASE}?page=${page}`;

        const res = await fetch(url, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
            }
        });

        console.log('Response fetchServices:', res);

        if (!res.ok) {
            throw new Error('Gagal mengambil data service');
        }

        const data = await res.json();
        console.log('Data JSON fetchServices:', data);

        // Kalau pakai all=true, data langsung array
        if (all) {
            return data;
        }

        // Kalau pakai pagination (default)
        return {
            services: data.data,
            current_page: data.current_page,
            last_page: data.last_page,
            total: data.total,
            per_page: data.per_page,
        };
    } catch (err) {
        console.error('Error fetchServices:', err);
        return all ? [] : {
            services: [],
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 5,
        };
    }
}


// Ambil service berdasarkan ID
export async function fetchServiceById(id) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
            }
        });

        if (!res.ok) throw new Error('Service tidak ditemukan');

        return await res.json(); // Langsung 1 objek service
    } catch (err) {
        console.error('Error fetchServiceById:', err);
        return null;
    }
}

// Tambah service
export async function createService(data) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(API_BASE, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(data),
        });

        const json = await res.json();

        if (!res.ok) {
            throw new Error(json.message || 'Gagal membuat service');
        }

        return json.data; // backend kirim { message, data }
    } catch (err) {
        console.error('Error createService:', err);
        return null;
    }
}

// Update service
export async function updateService(id, data) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}/${id}`, {
            method: 'PATCH', // sesuai dengan controller
            headers: { 
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(data),
        });

        const json = await res.json();

        if (!res.ok) {
            throw new Error(json.message || 'Gagal mengupdate service');
        }

        return json.data;
    } catch (err) {
        console.error('Error updateService:', err);
        return null;
    }
}

// Hapus service
export async function deleteService(id) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}/${id}`, { 
            method: 'DELETE',
            headers: { 'Authorization': `Bearer ${token}` },
        });

        const json = await res.json();

        if (!res.ok) {
            throw new Error(json.message || 'Gagal menghapus service');
        }

        return json.message;
    } catch (err) {
        console.error('Error deleteService:', err);
        return null;
    }
}

// Biar bisa dipanggil di Blade
window.fetchServices = fetchServices;
window.fetchServiceById = fetchServiceById;
window.createService = createService;
window.updateService = updateService;
window.deleteService = deleteService;
