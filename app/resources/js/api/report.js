const API_BASE = 'http://localhost:8000/api/reports';

export async function fetchReports() {
    try {
        const token = localStorage.getItem('api_token');
        const res = await fetch(API_BASE, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error(`Gagal mengambil data laporan (${res.status})`);
        const result = await res.json();
        console.log('Data laporan transaksi:', result);

        return Array.isArray(result) ? result : result.data ?? [];
    } catch (err) {
        console.error('Error fetchReports:', err);
        return [];
    }
}

export async function downloadReport(type = 'excel', params = '') {
    const token = localStorage.getItem('api_token');
    const url = `${API_BASE}/${type}?${params}`;

    try {
        const res = await fetch(url, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': type === 'excel'
                    ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    : 'application/pdf'
            }
        });

        if (!res.ok) {
            const text = await res.text();
            console.error('Gagal download:', text);
            throw new Error(`Gagal download ${type.toUpperCase()} (${res.status})`);
        }

        const blob = await res.blob();
        const fileName = `laporan-transaksi-${type}.${type === 'excel' ? 'xlsx' : 'pdf'}`;

        const blobUrl = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = blobUrl;
        a.download = fileName;
        document.body.appendChild(a);
        a.click();
        a.remove();

        setTimeout(() => window.URL.revokeObjectURL(blobUrl), 1000);
    } catch (err) {
        console.error(`Error downloadReport (${type}):`, err);
        alert(`Gagal download ${type.toUpperCase()}: ${err.message}`);
    }
}


window.fetchReports = fetchReports;
window.downloadReport = downloadReport;
