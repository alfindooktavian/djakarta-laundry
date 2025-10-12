const API_BASE = 'http://localhost:8000/api/services'; // URL API Laravel

// Ambil semua service
export async function fetchServices() {
    try {
        const token = localStorage.getItem('api_token');
        console.log('Token dari localStorage:', token);

        const res = await fetch(API_BASE, {
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

        return data; // Langsung array dari Service::all()
    } catch (err) {
        console.error('Error fetchServices:', err);
        return [];
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
