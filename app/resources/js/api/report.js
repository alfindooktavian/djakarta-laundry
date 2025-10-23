const API_BASE = 'http://localhost:8000/api/reports';

/**
 * Ambil semua laporan transaksi
 */
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

/**
 * Download laporan dalam format PDF
 */
export async function downloadReport(type = 'pdf', params = '') {
    const token = localStorage.getItem('api_token');
    const url = `${API_BASE}/${type}?${params}`;

    try {
        const res = await fetch(url, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/pdf'
            }
        });

        if (!res.ok) {
            const text = await res.text();
            console.error('Gagal download PDF:', text);
            throw new Error(`Gagal download PDF (${res.status})`);
        }

        const blob = await res.blob();
        const fileName = `laporan-transaksi-${new Date().toISOString().split('T')[0]}.pdf`;

        const blobUrl = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = blobUrl;
        a.download = fileName;
        document.body.appendChild(a);
        a.click();
        a.remove();

        setTimeout(() => window.URL.revokeObjectURL(blobUrl), 1000);
    } catch (err) {
        console.error('Error downloadReport (pdf):', err);
        alert(`Gagal download PDF: ${err.message}`);
    }
}

// Biar bisa dipanggil langsung dari Blade
window.fetchReports = fetchReports;
window.downloadReport = downloadReport;
