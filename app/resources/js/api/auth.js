// resources/js/api/auth.js

const API_BASE = `${import.meta.env.VITE_API_BASE_URL}`;

// Login user
export async function loginUser(email, password) {
    try {
        const res = await fetch(`${API_BASE}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password }),
        });

        const data = await res.json();

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
            await fetch(`${API_BASE}/logout`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                },
            });
        }
    } catch (err) {
        console.error('Error logoutUser:', err);
    } finally {
        // Selalu hapus data localStorage meski request gagal
        localStorage.removeItem('api_token');
        localStorage.removeItem('user');
    }

    return { message: 'Logout berhasil' };
}

window.loginUser = loginUser;
window.logoutUser = logoutUser;
