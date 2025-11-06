// resources/js/api/services.js

const API_BASE = `${import.meta.env.VITE_API_BASE_URL}/services`;

export async function fetchServices(page = 1, all = false) {
    try {
        const token = localStorage.getItem('api_token');
        const url = all ? `${API_BASE}?all=true` : `${API_BASE}?page=${page}`;

        const res = await fetch(url, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
            },
        });

        if (!res.ok) throw new Error('Gagal mengambil data service');

        const data = await res.json();
        return all
            ? data
            : {
                  services: data.data,
                  current_page: data.current_page,
                  last_page: data.last_page,
                  total: data.total,
                  per_page: data.per_page,
              };
    } catch (err) {
        console.error('Error fetchServices:', err);
        return all
            ? []
            : {
                  services: [],
                  current_page: 1,
                  last_page: 1,
                  total: 0,
                  per_page: 5,
              };
    }
}

export async function fetchServiceById(id) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
            },
        });

        if (!res.ok) throw new Error('Service tidak ditemukan');
        return await res.json();
    } catch (err) {
        console.error('Error fetchServiceById:', err);
        return null;
    }
}

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

        if (!res.ok) {
            const errData = await res.json();
            throw new Error(errData.message || 'Gagal membuat service');
        }

        return await res.json();
    } catch (err) {
        console.error('Error createService:', err);
        return null;
    }
}

export async function updateService(id, data) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(data),
        });

        if (!res.ok) {
            const errData = await res.json();
            throw new Error(errData.message || 'Gagal mengupdate service');
        }

        return await res.json();
    } catch (err) {
        console.error('Error updateService:', err);
        return null;
    }
}

export async function deleteService(id) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}/${id}`, {
            method: 'DELETE',
            headers: { Authorization: `Bearer ${token}` },
        });

        if (!res.ok) {
            const errData = await res.json();
            throw new Error(errData.message || 'Gagal menghapus service');
        }

        return await res.json();
    } catch (err) {
        console.error('Error deleteService:', err);
        return null;
    }
}

window.fetchServices = fetchServices;
window.fetchServiceById = fetchServiceById;
window.createService = createService;
window.updateService = updateService;
window.deleteService = deleteService;
