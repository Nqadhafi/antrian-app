<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Antrian Percetakan</title>

    <!-- Link ke CSS Bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/time.js') }}"></script>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid">
            <!-- Logo dan Nama Aplikasi -->
     
            <a class="navbar-brand" href="#" >
                <img src="{{ asset('assets/img/logo.webp') }}" alt="Logo" style="max-height: 2rem;"> 
            </a>

            <!-- Waktu Saat Ini di Sebelah Kanan -->
            <div class="ml-auto">
                <span id="current-time" class="text-dark fw-bold"></span>
            </div>
        </div>
    </nav>

    <!-- Header Konten -->
    <div class="container py-5 flex-grow-1 my-auto">
        <h1 class="text-center mb-4">Selamat Datang di Shabat Printing</h1>

        <!-- Menampilkan alert sukses jika ada -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Pilih Layanan -->
        <h2 class="text-center mb-4">Silahkan Pilih Layanan Kami</h2>

        <div class="row justify-content-center">
            @foreach ($categories as $category)
                <div class="col-md-6 mb-3">
                    <form action="/ambil-antrian/{{ $category->id }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="btn w-100 category-button text-white category-{{ $category->id }}" 
                                style="height: 15rem;">
                            <h4 class="text-center fw-bold">{{ $category->name }}</h4>
                            <small class="d-block text-center fw-bold">(Sisa Antrian: <span id="queue-{{ $category->id }}">{{ $category->queues_count }}</span>)</small>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
        
        
        
</div>
    </div>
    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Shabat Printing. All Rights Reserved.</p>
    </footer>

</body>
<script src="{{ asset ('/js/callQueue.js') }}"></script>

<script src="{{ asset ('/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset ('/js/pusher.min.js') }}"></script>
<script type="module">
    import { sendPrintRequest } from "{{ asset('js/queuePrinter.js') }}";
    const pusher = new Pusher('local-app-key', {
    cluster: 'mt1',
    wsHost: '192.168.100.102',
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
    reconnectionAttempts: Infinity, // Tidak ada batasan reconnect
    reconnectInterval: 5000, // Coba reconnect setiap 5 detik
});

const channel = pusher.subscribe('queue-updates');
channel.bind('App\\Events\\QueueUpdated', function (data) {
console.log('Event diterima:', data);

// Update jumlah sisa antrian
const queueElement = document.getElementById(`queue-${data.categoryId}`);
if (queueElement) {
    queueElement.innerText = data.remainingQueues;
}

// Mulai pemanggilan suara hanya jika berasal dari admin panel
if (data.isAdminCall && data.queueNumber) {
    // Ambil abjad dari queueNumber (misalnya, "A" dari "A-003")
    const type = data.queueNumber.split('-')[0]; // Pisahkan abjad sebelum "-"
    const number = parseInt(data.queueNumber.split('-')[1], 10); // Pisahkan angka setelah "-"

    console.log(`Tipe Antrian: ${type}, Nomor: ${number}`);

    // Generate audio queue dan mulai pemutaran
    const audioQueue = generateAudioQueue(type, number);
    playAudioQueue(audioQueue);
}
});
document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    });

                    const data = await response.json();
                    console.log('Respons dari Server:', data);

                    if (data.success) {
                        sendPrintRequest(data);
                    } else {
                        alert('Gagal mengambil antrian: ' + data.message);
                    }
                } catch (error) {
                    console.error('Kesalahan:', error);
                    alert('Kesalahan dalam proses pengambilan antrian.');
                }
            });
        });
</script>
</html>
