<div class="bg-gradient-to-br from-green-50 to-blue-50 border border-blue-100 rounded-2xl shadow-md hover:shadow-lg transition p-5 w-full mt-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-base font-semibold text-gray-800 uppercase tracking-wide">{{ $title }}</h2>
        <select id="{{ $id }}YearSelect"
            class="border border-blue-200 bg-white text-gray-700 rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm transition">
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

    // Load data dashboard
    async function loadDashboard(year = null) {
        const data = await window.fetchDashboard(year);

        const chartData = [
            data.total_services,
            data.total_orders,
            data.total_customers
        ];

        if (chartInstance) chartInstance.destroy();

        // Warna pastel lembut
        const pastelColors = [
            'rgba(134, 239, 172, 0.85)', // green-300
            'rgba(147, 197, 253, 0.85)', // blue-300
            'rgba(253, 186, 116, 0.85)'  // orange-300
        ];

        chartInstance = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Total Layanan', 'Total Pesanan', 'Total Pelanggan'],
                datasets: [{
                    data: chartData,
                    backgroundColor: pastelColors,
                    borderColor: pastelColors.map(c => c.replace('0.85', '1')),
                    borderWidth: 2,
                    hoverOffset: 10,
                    hoverBorderWidth: 2
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 13 },
                            color: '#374151',
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255,255,255,0.95)',
                        titleColor: '#111827',
                        bodyColor: '#374151',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Ambil data awal & isi dropdown tahun
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
