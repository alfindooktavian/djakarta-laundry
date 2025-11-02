<div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition p-5 w-full mt-6">
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
        const summaryData = {!! json_encode($summaryData) !!}; 
        // contoh struktur $summaryData = { "2025": { "layanan": 50, "penghasilan": 1000000, "customer": 20 }, ... }

        function renderChart(year) {
            const data = summaryData[year] || { layanan: 0, pesanan: 0, customer: 0 };

            if (window["{{ $id }}Chart"]) {
                window["{{ $id }}Chart"].destroy();
            }

            window["{{ $id }}Chart"] = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Total Layanan', 'Total Pesanan', 'Total Customer'],
        datasets: [{
            data: [data.layanan, data.pesanan, data.customer],
            backgroundColor: [
                'rgba(107, 114, 128, 0.8)',  // abu gelap
                'rgba(156, 163, 175, 0.8)',  // abu sedang
                'rgba(209, 213, 219, 0.8)'   // abu terang
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

        yearSelect.addEventListener('change', e => renderChart(e.target.value));
        renderChart(yearSelect.value);
    });
</script>
