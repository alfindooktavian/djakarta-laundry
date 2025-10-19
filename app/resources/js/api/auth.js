const API_BASE = 'http://localhost:8000/api'; 

// Login user
export async function loginUser(email, password) {
    try {
        const res = await fetch(`${API_BASE}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });

        const data = await res.json();
        console.log('Response login:', data);

        if (!res.ok) {
            throw new Error(data.message || 'Login gagal');
        }

        // Simpan token dan data user ke localStorage
        localStorage.setItem('api_token', data.token);
        localStorage.setItem('user', JSON.stringify(data.user));

        return data;
    } catch (err) {
        console.error('Error loginUser:', err);
        return { error: err.message };
    }
}

// Logout user
export async function logoutUser() {
    const token = localStorage.getItem('api_token');

    try {
        if (token) {
            const res = await fetch(`${API_BASE}/logout`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            });

            const data = await res.json();
            console.log('Response logout:', data);
        } else {
            console.warn('Token tidak ditemukan, langsung logout lokal.');
        }
    } catch (err) {
        console.error('Error logoutUser:', err);
    } finally {
        // 🔥 SELALU hapus data localStorage, apapun hasilnya
        localStorage.removeItem('api_token');
        localStorage.removeItem('user');
    }

    return { message: 'Logout berhasil' };
}

// Expose ke global supaya bisa dipanggil di Blade
window.loginUser = loginUser;
window.logoutUser = logoutUser;
