// resources/js/components/modal.js

/**
 * Fungsi untuk membuka modal berdasarkan ID
 * @param {string} id - ID modal yang ingin dibuka
 */
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

/**
 * Fungsi untuk menutup modal berdasarkan ID
 * @param {string} id - ID modal yang ingin ditutup
 */
function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

window.openModal = openModal;
window.closeModal = closeModal;

document.addEventListener('click', function (e) {
    const modals = document.querySelectorAll('[id$="Modal"]');
    modals.forEach(modal => {
        if (!modal.classList.contains('hidden') && e.target === modal) {
            closeModal(modal.id);
        }
    });
});
