<x-filament::page>
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

.j-center {
    justify-content: center;
    align-self: center;
    align-items: center;
}

.total-queues{
    font-size: 4rem;
}
#category-1{
    color: #00A2E9 ;
    border: 0.5rem solid #00A2E9;
}
#category-2{
    color: #e7c101;
    border: 0.5rem solid #e7c101;
}
#category-3{
    color: #E40082 ;
    border: 0.5rem solid #E40082;
}
#category-4{
    color: #332C2B ;
    border: 0.5rem solid #332C2B;
}
    </style>
    <div class="grid md:grid-cols-4 gap-3 text-center">
        @foreach ($this->categories as $category)
            <div id="category-{{ $category->id }}" class="bg-white p-2 rounded shadow">
                <h3 class="text-lg  text-center"><strong>{{ $category->name }}</strong></h3>
                <p class="text-center">Total Antrian:</p> 
                <h1 class="total-queues ">{{ $category->totalQueues }}</h1>
                <p>Belum Dipanggil: <strong class="not-called-queues">{{ $category->notCalledQueues }}</strong></p>
                <p>Sudah Dipanggil: <strong class="called-queues">{{ $category->calledQueues }}</strong></p>
            </div>
        @endforeach
    </div>
    <div class="flex j-center mb-4 gap-4" >
        <!-- Tombol Panggil Selanjutnya -->
        <button wire:click="callNext" class="px-4 py-2 text-white rounded" style="background-color: rgb(2, 90, 223);">
            Panggil Selanjutnya
        </button>

        <!-- Tombol Panggil Ulang -->
        <button wire:click="recallLast" class="px-4 py-2 text-white rounded" style="background-color: rgb(43, 202, 3);">
            Panggil Ulang
        </button>

        <!-- Tombol Reset Antrian -->
        <button wire:click="resetQueue" class="px-4 py-2 text-white rounded" style="background-color: rgb(226, 11, 11);">
            Reset Antrian
        </button>
    </div>
<div class="flex j-center">
    <!-- Tabel Daftar Antrian -->
    <table class="min-w-full table-auto">
        <thead class="bg-gray-100 border">
            <tr>
                <th class="px-4 py-2 text-left">Nomor Antrian</th>
                <th class="px-4 py-2 text-left">Kategori</th>
                <th class="px-4 py-2 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($this->queues as $queue)
                <tr class="border-b bg-white">
                    <td class="px-4 py-2">{{ $queue->number }}</td>
                    <td class="px-4 py-2">{{ $queue->category->name }}</td>
                    <td class="px-4 py-2">
                        @if ($queue->is_called)
                            <span class="rounded text-white p-2" style="background-color: rgb(43, 202, 3);">Dipanggil</span>
                        @else
                            <span class="rounded text-white p-2" style="background-color: rgb(226, 11, 11);">Belum Dipanggil</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Notifikasi -->
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-4"></div>
    <script src="{{ asset ('js/pusher.min.js') }}"></script>
    <!-- Script untuk Real-Time Update -->
    <script>
        // Konfigurasi Pusher
        const pusher = new Pusher('local-app-key', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            wsHost: '{{ env('PUSHER_HOST') }}',
            wsPort: '{{ env('PUSHER_PORT') }}',
            forceTLS: false,
            disableStats: true,
            reconnectionAttempts: Infinity,
            reconnectInterval: 5000,
        });

        // Subscribe ke channel queue-updates
        const channel = pusher.subscribe('queue-updates');

        // Listener untuk event QueueUpdated
        channel.bind('App\\Events\\QueueUpdated', function (data) {
            console.log('Event diterima:', data);

            const tableBody = document.querySelector('table tbody');
            if (!tableBody) return;

            // Jika event berasal dari user (isAdminCall = false), tambahkan baris ke tabel
            if (!data.isAdminCall) {
                const newRow = document.createElement('tr');
                newRow.className = 'border-b bg-white';
                newRow.innerHTML = `
                    <td class="px-4 py-2">${data.queueNumber}</td>
                    <td class="px-4 py-2">${data.categoryName}</td>
                    <td class="px-4 py-2">
                        <span class="rounded text-white p-2" style="background-color: rgb(226, 11, 11);">Belum Dipanggil</span>
                    </td>
                `;
                tableBody.appendChild(newRow);

                // Update jumlah sisa antrian untuk kategori
                const remainingElement = document.querySelector(`[data-category-id="${data.categoryId}"] .remaining-queues`);
                if (remainingElement) {
                    remainingElement.textContent = `Sisa Antrian: ${data.remainingQueues}`;
                }

                // Tampilkan notifikasi
                showNotification('success', `Antrian baru ditambahkan: ${data.queueNumber}`);
            }
            if (data.categoryId) {
        const categoryElement = document.getElementById(`category-${data.categoryId}`);
        if (categoryElement) {
            // Ambil elemen terkait
            const totalElement = categoryElement.querySelector('.total-queues');
            const remainingElement = categoryElement.querySelector('.not-called-queues');
            const calledElement = categoryElement.querySelector('.called-queues');

            // Ambil nilai dari elemen atau default ke 0
            const currentTotal = parseInt(totalElement.textContent) || 0;
            const currentRemaining = parseInt(remainingElement.textContent) || 0;
            const currentCalled = parseInt(calledElement.textContent) || 0;

            // Update nilai berdasarkan event
            const newRemaining = data.remainingQueues || 0;
            const newTotal = currentTotal + (data.queueNumber ? 1 : 0); // Tambahkan 1 jika ada nomor antrian baru
            const newCalled = newTotal - newRemaining;

            // Set nilai baru ke elemen
            totalElement.textContent = newTotal;
            remainingElement.textContent = newRemaining;
            calledElement.textContent = newCalled >= 0 ? newCalled : 0;
        }
    }
        });

        // Fungsi untuk menampilkan notifikasi
        function showNotification(type, message) {
            const notificationContainer = document.getElementById('notification-container');
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
    </script>
</x-filament::page>
