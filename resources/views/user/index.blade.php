<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Antrian Percetakan</title>
    
    <!-- Link ke CSS Bootstrap dan aplikasi -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">


</head>
<body class="bg-light">

    <!-- Header -->
    <div class="container py-5">
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

        <!-- Template untuk Print Nomor Antrian -->
        <div id="print-template" class="mt-5 p-3 border rounded" style="display: none;">
            <h2 class="text-center">Nomor Antrian Anda</h2>
            <p class="text-center">Kategori: <span id="print-category"></span></p>
            <p class="text-center">Nomor: <span id="print-number"></span></p>
            <p class="text-center">Waktu: <span id="print-timestamp"></span></p>
        </div>
    </div>

</body>

<script src="{{ asset ('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset ('js/pusher.min.js') }}"></script>
<script>
    const pusher = new Pusher('local-app-key', {
        cluster: 'mt1',
        wsHost: '127.0.0.1',
        wsPort: 6001,
        forceTLS: false,
        disableStats: true,
    });

    const channel = pusher.subscribe('queue-updates');
    channel.bind('App\\Events\\QueueUpdated', function (data) {
        console.log('Event diterima di User:', data);

        // Update jumlah sisa antrian
        const queueElement = document.getElementById(`queue-${data.categoryId}`);
        if (queueElement) {
            queueElement.innerText = data.remainingQueues;
        } else {
            console.error('Elemen untuk kategori ini tidak ditemukan.');
        }
    });

    // Fungsi untuk mencetak antrian
    function printQueue(queue) {
        document.getElementById('print-category').innerText = queue.category_name;
        document.getElementById('print-number').innerText = queue.queue.number;
        document.getElementById('print-timestamp').innerText = queue.timestamp;

        const printTemplate = document.getElementById('print-template').innerHTML;
        const newWindow = window.open('', '', 'width=300,height=400');
        newWindow.document.write(printTemplate);
        newWindow.print();
        newWindow.close();
    }

    // Handle respons dari pengambilan antrian
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log('Respons dari Server:', data);

                    if (data.success) {
                        printQueue(data);
                    } else {
                        alert('Gagal mengambil antrian: ' + (data.message || 'Kesalahan tidak diketahui.'));
                    }
                } else {
                    console.error('Respons HTTP Gagal:', response);
                    alert('Gagal mengirim permintaan.');
                }
            } catch (error) {
                console.error('Kesalahan dalam proses fetch:', error);
                alert('Terjadi kesalahan dalam proses permintaan.');
            }
        });
    });
</script>
</html>
