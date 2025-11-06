const API_BASE = 'http://localhost:8000/api/dashboard';

// Ambil data ringkasan dashboard dan data chart
export async function fetchDashboard(selectedYear = null) {  
    try {
        const token = localStorage.getItem('api_token');
        const url = selectedYear ? `${API_BASE}?year=${selectedYear}` : API_BASE;

        const res = await fetch(url, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        });

        if (!res.ok) {
            throw new Error('Gagal mengambil data dashboard');
        }

        const data = await res.json();
        const totals = data.totals || {};

        return {
            total_customers: totals.total_customers || 0,
            total_services: totals.total_services || 0,
            total_orders: totals.total_orders || 0,
            active_orders: totals.active_orders || 0,
            completed_orders: totals.completed_orders || 0,
            orders_per_year: data.orders_per_year || [],
            customers_per_year: data.customers_per_year || [],
            services_per_year: data.services_per_year || [],
            income_per_month: data.income_per_month || {},   
            income_per_year: data.income_per_year || [],    
        };
    } catch (err) {
        console.error('Error fetchDashboard:', err);
        return {
            total_customers: 0,
            total_services: 0,
            total_orders: 0,
            active_orders: 0,
            completed_orders: 0,
            orders_per_year: [],
            customers_per_year: [],
            services_per_year: [],
            income_per_month: {},   
            income_per_year: [],    
        };
    }
}

window.fetchDashboard = fetchDashboard;
