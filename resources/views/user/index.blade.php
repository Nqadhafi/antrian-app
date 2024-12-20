<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Antrian Percetakan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }
        .category-button {
            display: inline-block;
            padding: 15px 30px;
            margin: 10px;
            font-size: 18px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .category-button:hover {
            background-color: #0056b3;
        }
        .alert {
            margin: 20px 0;
            padding: 15px;
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Selamat Datang di Antrian Percetakan</h1>

    @if (session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    <h2>Pilih Layanan</h2>
    <div>
        @foreach ($categories as $category)
            <form action="/ambil-antrian/{{ $category->id }}" method="POST" style="display: inline-block;">
                @csrf
                <button type="submit" class="category-button">
                    {{ $category->name }} <br>
                    <small>(Sisa Antrian: <span id="queue-{{ $category->id }}">{{ $category->queues_count }}</span>)</small>
                </button>
            </form>
        @endforeach
    </div>
    <div id="print-template" style="display: none;">
        <h1>Nomor Antrian Anda</h1>
        <p>Kategori: <span id="print-category"></span></p>
        <p>Nomor: <span id="print-number"></span></p>
        <p>Waktu: <span id="print-timestamp"></span></p>
    </div>
    
</body>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
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
