<x-filament::page>
    <!-- Container Notifikasi -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-4"></div>


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

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Konfigurasi Pusher
        const pusher = new Pusher('local-app-key', {
            cluster: 'mt1',
            wsHost: '127.0.0.1',
            wsPort: 6001,
            forceTLS: false,
            disableStats: true,
        });
    
        // Subscribe ke channel queue-updates
        const channel = pusher.subscribe('queue-updates');
    
        // Listener untuk event QueueUpdated
        channel.bind('App\\Events\\QueueUpdated', function (data) {
            console.log('Event diterima di Admin:', data);
    
            // Validasi data event
            if (!data || !data.categoryId || !data.remainingQueues) {
                console.error('Data event tidak valid:', data);
                return;
            }
    
            // Update jumlah sisa antrian
            const queueElement = document.querySelector(`[data-category-id="${data.categoryId}"] .remaining-queues`);
            if (queueElement) {
                console.log('Memperbarui jumlah sisa antrian.');
                queueElement.innerText = `Sisa Antrian: ${data.remainingQueues}`;
            } else {
                console.error('Elemen untuk kategori ini tidak ditemukan!');
            }
    
            // Trigger notifikasi untuk admin
            const type = 'success';
            const message = `Antrian baru ditambahkan untuk kategori ID ${data.categoryId}!`;
    
            console.log('Memancarkan event notify dengan:', { type, message });
    
            window.dispatchEvent(new CustomEvent('notify', {
                detail: { type, message },
            }));
        });
    
        // Fungsi untuk menampilkan notifikasi di admin
        function showNotification(type, message) {
            const notificationContainer = document.getElementById('notification-container');
    
            // Validasi keberadaan container notifikasi
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
    
            console.log('Menambahkan notifikasi ke container:', notification);
    
            // Tambahkan elemen notifikasi ke container
            notificationContainer.appendChild(notification);
    
            // Hapus notifikasi setelah beberapa detik
            setTimeout(() => {
                notification.style.transition = 'opacity 0.5s ease';
                notification.style.opacity = 0;
    
                setTimeout(() => {
                    notification.remove();
                }, 500); // Tunggu animasi selesai
            }, 3000);
        }
    
        // Listener untuk event notify
        document.addEventListener('notify', (event) => {
            console.log('Event Notify Diterima:', event.detail);
    
            // Validasi data notifikasi
            if (!event.detail) {
                console.error('Data event notify kosong!');
                return;
            }
    
            const { type, message } = event.detail;
            console.log('Memproses notifikasi:', { type, message });
    
            // Tampilkan notifikasi
            showNotification(type, message);
        });
    
        // Tes manual untuk memastikan fungsi notifikasi bekerja
        function testNotification() {
            console.log('Tes Notifikasi Manual...');
            window.dispatchEvent(new CustomEvent('notify', {
                detail: { type: 'success', message: 'Tes Manual: Notifikasi berhasil!' },
            }));
        }
    
        // Jalankan tes manual setelah 1 detik
        setTimeout(testNotification, 1000);
    
        console.log('Pusher dan Event Listener siap!');
    </script>
    
    
</x-filament::page>
