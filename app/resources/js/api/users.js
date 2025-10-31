const API_BASE = 'http://localhost:8000/api/users'; // URL lengkap API Laravel

// Ambil semua user
export async function fetchUsers(page = 1, all = false) {
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

        console.log('Response fetchUsers:', res);

        if (!res.ok) {
            throw new Error('Gagal mengambil data users');
        }

        const data = await res.json();
        console.log('Data JSON fetchUsers:', data);

        // Kalau pakai all=true, data langsung array
        if (all) {
            return data;
        }

        // Kalau pakai pagination (default)
        return {
            users: data.data,
            current_page: data.current_page,
            last_page: data.last_page,
            total: data.total,
            per_page: data.per_page,
        };
    } catch (err) {
        console.error('Error fetchUsers:', err);
        return all ? [] : {
            users: [],
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 5,
        };
    }
}


// Ambil user berdasarkan ID
export async function fetchUserById(id) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}/${id}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
            }
        });

        if (!res.ok) throw new Error('User tidak ditemukan');

        return await res.json();
    } catch (err) {
        console.error(err);
        return null;
    }
}

// Tambah user
export async function createUser(data) {
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
            throw new Error(errData.message || 'Gagal membuat user');
        }

        return await res.json();
    } catch (err) {
        console.error(err);
        return null;
    }
}

// Update user
export async function updateUser(id, data) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}/${id}`, {
            method: 'PATCH', // Laravel API kamu pakai PATCH, bukan PUT
            headers: { 
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(data),
        });

        if (!res.ok) {
            const errData = await res.json();
            throw new Error(errData.message || 'Gagal mengupdate user');
        }

        return await res.json();
    } catch (err) {
        console.error(err);
        return null;
    }
}

// Hapus user
export async function deleteUser(id) {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(`${API_BASE}/${id}`, { 
            method: 'DELETE',
            headers: { 'Authorization': `Bearer ${token}` },
        });

        if (!res.ok) {
            const errData = await res.json();
            throw new Error(errData.message || 'Gagal menghapus user');
        }

        return await res.json();
    } catch (err) {
        console.error(err);
        return null;
    }
}


// Expose ke global supaya bisa dipanggil di Blade
window.fetchUsers = fetchUsers;
window.fetchUserById = fetchUserById;
window.createUser = createUser;
window.updateUser = updateUser;
window.deleteUser = deleteUser;