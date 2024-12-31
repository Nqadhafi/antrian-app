<x-filament::page>
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-4"></div>
    <style>
        #notification-container div {
    z-index: 9999;
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: green;
    color: white;
    padding: 10px;
    border-radius: 5px;
}

    </style>
    <!-- Container Notifikasi -->
    


    <h1 class="text-xl font-bold">Kelola Antrian</h1>
    
    <div class="space-y-6 mt-6">
        @foreach ($this->categories as $category)
            <div class="border p-4 rounded shadow" data-category-id="{{ $category->id }}">
                <h2 class="text-lg font-semibold category-name">{{ $category->name }}</h2>
                <p class="remaining-queues">Sisa Antrian: {{ $category->queues_count }}</p>

                <div class="flex space-x-4 mt-4">
                    <!-- Tombol Panggil Selanjutnya -->
                    <form wire:submit.prevent="callNext({{ $category->id }})">
                        <button type="submit" class="px-4 py-2 text-white rounded" style="background-color: blue;">
                            Panggil Selanjutnya
                        </button>
                    </form>

                    <!-- Tombol Panggil Ulang -->
                    <form wire:submit.prevent="recallLast({{ $category->id }})">
                        <button type="submit" class="px-4 py-2 text-white rounded" style="background-color: green;">
                            Panggil Ulang
                        </button>
                    </form>

                    <!-- Tombol Reset Antrian -->
                    <form wire:submit.prevent="resetQueue({{ $category->id }})">
                        <button type="submit" class="px-4 py-2 text-white rounded" style="background-color: red;">
                            Reset Antrian
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <script src="{{ asset ('js/pusher.min.js') }}"></script>
    <script>
        // Konfigurasi Pusher
        const pusher = new Pusher('local-app-key', {
            cluster: 'mt1',
            wsHost: '192.168.100.102',
            wsPort: 6001,
            forceTLS: false,
            disableStats: true,
            reconnectionAttempts: Infinity, // Tidak ada batasan reconnect
            reconnectInterval: 5000, // Coba reconnect setiap 5 detik
        });
    
        // Subscribe ke channel queue-updates
        const channel = pusher.subscribe('queue-updates');
    
        // Listener untuk event QueueUpdated
        channel.bind('App\\Events\\QueueUpdated', function (data) {
            console.log('Event diterima di Admin:', data);
    
            // Validasi data event
            if (!data || typeof data.categoryId === 'undefined' || typeof data.remainingQueues === 'undefined') {
                console.error('Data event tidak valid:', data);
                return;
            }
    
            // Update jumlah sisa antrian
            const queueElement = document.querySelector(`[data-category-id="${data.categoryId}"] .remaining-queues`);
            if (queueElement) {
                queueElement.innerText = `Sisa Antrian: ${data.remainingQueues}`;
            } else {
                console.warn(`Elemen untuk kategori ID ${data.categoryId} tidak ditemukan.`);
            }
    
            // Trigger notifikasi untuk admin
            const type = 'success';
            const message = `Antrian baru ditambahkan untuk kategori ${data.categoryName}!`;
    
            console.log('Memancarkan event notify dengan:', { type, message });
    
            try {
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: { type, message },
                }));
            } catch (error) {
                console.error('Error saat memancarkan event notify:', error);
            }
        });
    
        // Fungsi untuk menampilkan notifikasi di admin
        function showNotification(type, message) {
            console.log('Memasuki fungsi showNotification dengan:', { type, message });
    
            const notificationContainer = document.getElementById('notification-container');
    
            if (!notificationContainer) {
                console.error('Container notifikasi tidak ditemukan di DOM.');
                return;
            }
    
            // Buat elemen notifikasi
            const notification = document.createElement('div');
            notification.className = `p-4 rounded-lg shadow-md text-white ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.innerHTML = `<p>${message}</p>`;
    
            notificationContainer.appendChild(notification);
    
            // Log setelah elemen ditambahkan
            console.log('Notifikasi berhasil ditambahkan ke container.');
    
            setTimeout(() => {
                notification.style.transition = 'opacity 0.5s ease';
                notification.style.opacity = 0;
    
                setTimeout(() => {
                    notification.remove();
                }, 500); // Tunggu animasi selesai
            }, 3000);
        }
    
        // Listener untuk event notify
        document.addEventListener('DOMContentLoaded', function () {
            window.addEventListener('notify', function (event) {
                const { type, message } = event.detail;

                const notificationContainer = document.getElementById('notification-container');
                if (!notificationContainer) return;

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
            });
        });
    
       
    </script>
    
    
    
</x-filament::page>
