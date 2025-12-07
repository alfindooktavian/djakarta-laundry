// resources/js/api/reports.js

const API_BASE = `${import.meta.env.VITE_API_BASE_URL}/reports`;

export async function fetchReports(page = 1, all = false) {
    try {
      const token = localStorage.getItem("api_token");
      const url = all ? `${API_BASE}?all=true` : `${API_BASE}?page=${page}`;
  
      const res = await fetch(url, {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json",
        },
      });
  
      if (!res.ok) throw new Error("Gagal mengambil data laporan");
  
      const data = await res.json();
  
      return all
        ? data
        : {
            reports: data.data,
            current_page: data.current_page,
            last_page: data.last_page,
            total: data.total,
            per_page: data.per_page,
          };
    } catch (err) {
      console.error("❌ Error fetchReports:", err);
  
      Swal.fire({
        icon: "error",
        title: "Gagal Memuat Data",
        text: "Tidak dapat mengambil laporan. Coba lagi nanti.",
      });
  
      return all
        ? []
        : {
            reports: [],
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 10,
          };
    }
  }
  

export async function downloadReport(type = 'pdf', params = '') {
    try {
        const token = localStorage.getItem('api_token');
        const url = `${API_BASE}/${type}?${params}`;

        const res = await fetch(url, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/pdf',
            },
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
        console.error('Error downloadReport:', err);
        alert(`Gagal download PDF: ${err.message}`);
    }
}

window.fetchReports = fetchReports;
window.downloadReport = downloadReport;
