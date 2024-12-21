import './bootstrap';
document.addEventListener('DOMContentLoaded', function () {
    // Fungsi untuk mendaftarkan listener
    function registerNotifyListener() {
        document.addEventListener('notify', (event) => {
            console.log('Event Notify Diterima:', event.detail);

            if (!event.detail) {
                console.error('Data event notify kosong!');
                return;
            }

            const { type, message } = event.detail;
            console.log('Memproses notifikasi:', { type, message });

            showNotification(type, message);
        });
    }

    // Daftarkan listener saat pertama kali
    registerNotifyListener();

    // Pastikan listener didaftarkan kembali setelah Livewire merender ulang
    Livewire.hook('message.processed', () => {
        console.log('Livewire DOM di-reload, mendaftarkan listener kembali.');
        registerNotifyListener();
    });
});

// Fungsi untuk menampilkan notifikasi
function showNotification(type, message) {
    const notificationContainer = document.getElementById('notification-container');

    if (!notificationContainer) {
        console.error('Container notifikasi tidak ditemukan di DOM.');
        return;
    }

    const notification = document.createElement('div');
    notification.className = `p-4 rounded-lg shadow-md text-white ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.innerHTML = `<p>${message}</p>`;

    notificationContainer.appendChild(notification);

    setTimeout(() => {
        notification.style.transition = 'opacity 0.5s ease';
        notification.style.opacity = 0;

        setTimeout(() => {
            notification.remove();
        }, 500);
    }, 3000);
}
