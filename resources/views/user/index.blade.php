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
    <nav class="navbar navbar-expand-lg navbar-light bg-primary">
        <div class="container-fluid">
            <!-- Logo dan Nama Aplikasi -->
     
            <a class="navbar-brand" href="#" >
                <img src="{{ asset('assets/img/logo.webp') }}" alt="Logo" style="max-height: 2rem;"> 
            </a>

            <!-- Waktu Saat Ini di Sebelah Kanan -->
            <div class="ml-auto">
                <span id="current-time" class="text-white"></span>
            </div>
        </div>
    </nav>

    <!-- Header Konten -->
    <div class="container py-5 flex-grow-1 my-auto">
        <h1 class="text-center mb-4">Selamat Datang di Antrian Percetakan</h1>

        <!-- Menampilkan alert sukses jika ada -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Pilih Layanan -->
        <h2 class="text-center mb-4">Pilih Layanan</h2>

        <div class="row justify-content-center">
            @foreach ($categories as $category)
                <div class="col-md-3 mb-3">
                    <form action="/ambil-antrian/{{ $category->id }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary w-100 category-button">
                            <h4 class="text-center">{{ $category->name }}</h4>
                            <small class="d-block text-center">(Sisa Antrian: <span id="queue-{{ $category->id }}">{{ $category->queues_count }}</span>)</small>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <div id="print-template" style="display: none;">
            <div style="font-family: Arial, sans-serif; width: 58mm; text-align: center; margin: 0; padding: 0;">
                <img src="{{ asset('assets/img/Logo.webp') }}" alt="Logo Perusahaan" style="width: 50px; margin: 10px auto;">
                <h2 style="margin: 0; font-size: 16px;">Nomor Antrian Anda</h2>
                <p style="margin: 10px 0; font-size: 12px;">Kategori: <span id="print-category"></span></p>
                <p style="font-size: 24px; font-weight: bold; margin: 20px 0;">
                    <span id="print-number"></span>
                </p>
                <p style="margin: 10px 0; font-size: 12px;">Waktu: <span id="print-timestamp"></span></p>
                <p style="margin: 10px 0; font-size: 12px;">Sisa Antrian: <span id="print-remaining-queues"></span></p>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="bg-primary text-white text-center py-3">
        <p>&copy; 2024 Shabat Printing. All Rights Reserved.</p>
    </footer>

</body>
<script src="{{ asset ('/js/callQueue.js') }}"></script>

<script src="{{ asset ('/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset ('/js/pusher.min.js') }}"></script>
<script src="{{ asset ('/js/pusherClientConfig.min.js') }}"></script>
<script>
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
    // Fungsi untuk mencetak antrian
    function printQueue(queue) {
        // Update konten template
        document.getElementById('print-category').innerText = queue.category_name;
        document.getElementById('print-number').innerText = queue.queue.number;
        document.getElementById('print-timestamp').innerText = queue.timestamp;
        document.getElementById('print-remaining-queues').innerText = queue.remaining_queues;

        // Ambil HTML template
        const printTemplate = `
            <html>
            <head>
                <title>Cetak Nomor Antrian</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        width: 58mm;
                        margin: 0;
                        padding: 0;
                        text-align: center;
                    }
                    h2 {
                        margin: 0;
                        font-size: 16px;
                    }
                    p {
                        margin: 10px 0;
                        font-size: 12px;
                    }
                    .queue-number {
                        font-size: 24px;
                        font-weight: bold;
                        margin: 20px 0;
                    }
                    img {
                        width: 50px;
                        margin: 10px auto;
                    }
                </style>
            </head>
            <body>
                <img src="{{ asset('images/logo.png') }}" alt="Logo Perusahaan">
                <h2>Nomor Antrian Anda</h2>
                <p>Kategori: ${queue.category_name}</p>
                <p class="queue-number">${queue.queue.number}</p>
                <p>Waktu: ${queue.timestamp}</p>
                <p>Sisa Antrian: ${queue.remaining_queues}</p>
            </body>
            </html>
        `;

        // Buka jendela baru untuk mencetak
        const newWindow = window.open('', '', 'width=300,height=400');
        newWindow.document.open();
        newWindow.document.write(printTemplate);
        newWindow.document.close();

        // Cetak dan tutup jendela
        newWindow.print();
        newWindow.onafterprint = () => {
            newWindow.close();
        };
    }

    // Handle respons dari pengambilan antrian
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
                    printQueue(data);
                } else {
                    alert('Gagal mengambil antrian: ' + data.message);
                }
            } catch (error) {
                alert('Kesalahan dalam proses cetak: ' + error.message);
            }
        });
    });
</script>
</html>
