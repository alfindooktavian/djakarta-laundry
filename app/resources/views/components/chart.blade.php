<div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition p-5  w-full mt-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-base font-semibold text-gray-700 uppercase tracking-wide">{{ $title }}</h2>
        <select id="{{ $id }}YearSelect"
            class="border border-gray-300 bg-gray-50 text-gray-700 rounded-lg px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @foreach ($years as $year)
                <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
        </select>
    </div>

    <div class="h-70">
        <canvas id="{{ $id }}"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById("{{ $id }}").getContext("2d");
        const yearSelect = document.getElementById("{{ $id }}YearSelect");
        const incomeData = {!! json_encode($incomeData) !!};

        function renderChart(year) {
            const chartData = incomeData[year] || [];

            if (window["{{ $id }}Chart"]) {
                window["{{ $id }}Chart"].destroy();
            }

            window["{{ $id }}Chart"] = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: `Rp (Penghasilan ${year})`,
                        data: chartData,
                        backgroundColor: 'rgba(156, 163, 175, 0.8)',
                        borderColor: 'rgba(107, 114, 128, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: "#6b7280", font: { size: 10 } },
                            grid: { color: "#e5e7eb" }
                        },
                        x: {
                            ticks: { color: "#6b7280", font: { size: 10 } },
                            grid: { display: false }
                        }
                    }
                }
            });
        }

        yearSelect.addEventListener('change', e => renderChart(e.target.value));
        renderChart(yearSelect.value);
    });
</script>
