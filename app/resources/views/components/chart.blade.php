<div class="bg-gradient-to-br from-green-50 to-blue-50 border border-blue-100 rounded-2xl shadow-sm hover:shadow-md transition p-5 w-full mt-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-base font-semibold text-gray-800 uppercase tracking-wide">{{ $title }}</h2>
        <select id="{{ $id }}YearSelect"
            class="border border-blue-200 bg-white text-gray-700 rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 shadow-sm">
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
    let dropdownInitialized = false;

    // Ambil data dari API dashboard
    async function loadChart(year = null) {
        const data = await fetchDashboard(year);

        // Isi dropdown tahun (hanya sekali)
        if (!dropdownInitialized) {
            const years = data.income_per_year || [];
            yearSelect.innerHTML = '<option value="">Semua Tahun</option>';
            years.forEach(y => {
                const option = document.createElement('option');
                option.value = y;
                option.textContent = y;
                yearSelect.appendChild(option);
            });
            dropdownInitialized = true;
        }

        const selectedYear = year || yearSelect.value || "";
        const incomeData = data.income_per_month || {};
        const monthlyIncome = Object.values(incomeData);

        if (chartInstance) chartInstance.destroy();

        // Palet warna lembut untuk tiap bulan
        const pastelColors = [
            'rgba(134, 239, 172, 0.8)', // green-300
            'rgba(147, 197, 253, 0.8)', // blue-300
            'rgba(167, 243, 208, 0.8)', // emerald-200
            'rgba(196, 181, 253, 0.8)', // purple-300
            'rgba(254, 240, 138, 0.8)', // yellow-200
            'rgba(125, 211, 252, 0.8)', // sky-300
            'rgba(253, 186, 116, 0.8)', // orange-300
            'rgba(244, 114, 182, 0.8)', // pink-300
            'rgba(186, 230, 253, 0.8)', // light blue
            'rgba(167, 243, 208, 0.8)', // emerald again
            'rgba(255, 220, 160, 0.8)', // soft gold
            'rgba(221, 214, 254, 0.8)'  // violet-200
        ];

        // Render Chart dengan warna lembut
        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: `Rp (Penghasilan ${selectedYear || 'Semua Tahun'})`,
                    data: monthlyIncome,
                    backgroundColor: pastelColors,
                    borderColor: pastelColors.map(c => c.replace('0.8', '1')),
                    borderWidth: 1,
                    borderRadius: 8,
                    hoverBackgroundColor: pastelColors.map(c => c.replace('0.8', '1'))
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString();
                            }
                        },
                        backgroundColor: 'rgba(255,255,255,0.9)',
                        titleColor: '#111827',
                        bodyColor: '#374151',
                        borderColor: '#e5e7eb',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: "#374151", font: { size: 11 } },
                        grid: { color: "rgba(209,213,219,0.3)" }
                    },
                    x: {
                        ticks: { color: "#374151", font: { size: 11 } },
                        grid: { display: false }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    }

    yearSelect.addEventListener('change', e => loadChart(e.target.value));
    loadChart();
});
</script>
