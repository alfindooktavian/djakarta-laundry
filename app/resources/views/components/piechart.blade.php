<div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition p-5 w-full mt-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-base font-semibold text-gray-700 uppercase tracking-wide">{{ $title }}</h2>
        <select id="{{ $id }}YearSelect"
            class="border border-gray-300 bg-gray-50 text-gray-700 rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Tahun</option>
        </select>
    </div>


<div class="h-70">
    <canvas id="{{ $id }}"></canvas>
</div>


</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", async function() {
    const ctx = document.getElementById("{{ $id }}").getContext("2d");
    const yearSelect = document.getElementById("{{ $id }}YearSelect");
    let chartInstance = null;

   
    async function loadDashboard(year = null) {
        const data = await window.fetchDashboard(year);

        
        const chartData = [
            data.total_services,
            data.total_orders,
            data.total_customers
        ];

        
        if (chartInstance) chartInstance.destroy();

        chartInstance = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Total Layanan', 'Total Pesanan', 'Total Pelanggan'],
                datasets: [{
                    data: chartData,
                    backgroundColor: [
                        'rgba(107, 114, 128, 0.8)',
                        'rgba(156, 163, 175, 0.8)',
                        'rgba(209, 213, 219, 0.8)'
                    ],
                    borderColor: [
                        'rgba(107, 114, 128, 1)',
                        'rgba(156, 163, 175, 1)',
                        'rgba(209, 213, 219, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 12 }, color: '#6b7280' }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ": " + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    
    const initialData = await window.fetchDashboard();
    const years = Array.from(new Set([
        ...initialData.orders_per_year.map(o => o.year),
        ...initialData.customers_per_year.map(c => c.year),
        ...initialData.services_per_year.map(s => s.year)
    ])).sort((a,b) => a - b);

    years.forEach(y => {
        const option = document.createElement('option');
        option.value = y;
        option.textContent = y;
        yearSelect.appendChild(option);
    });

    
    yearSelect.addEventListener('change', e => loadDashboard(e.target.value));

    
    loadDashboard(yearSelect.value || null);
});
</script>
