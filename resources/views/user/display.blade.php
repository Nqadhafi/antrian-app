<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Display Antrian Real-Time</title>

    <!-- Link ke CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>
<body class="bg-light vh-100 d-flex flex-column">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Antrian Real-Time</a>
            <div class="ml-auto">
                <span id="current-time" class="text-white"></span>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container-fluid flex-grow-1">
        <div class="row h-100">
            <!-- Nomor Panggilan -->
            <div class="col-md-4 d-flex align-items-center justify-content-center bg-primary text-white">
                <div class="text-center">
                    <h2>Nomor Panggilan</h2>
                    <p id="current-number" class="display-1 fw-bold">--</p>
                </div>
            </div>

            <!-- Video Player dan Informasi Antrian -->
            <div class="col-md-8 bg-light d-flex flex-column">
                <!-- Video Player -->
                @if ($video)
                    <div class="video-container mb-3 flex-grow-0">
                        <video controls autoplay loop class="rounded" style="height: 30rem; width:100%;">
                            <source src="{{ asset('storage/' . $video->path) }}" type="video/mp4">
                            Browser Anda tidak mendukung video.
                        </video>
                    </div>
                @endif

                <!-- Informasi Antrian -->
                <div class="container  flex-grow-1">
                    <div class="row">
                        @foreach ($categories as $category)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3 class="card-title">{{ $category->name }}</h3>
                                        <p>
                                            <strong>Antrian Terakhir:</strong>
                                            <h3 class="fw-bold">
                                                <span id="last-queue-{{ $category->id }}">{{ $category->last_queue ?? 'Belum Ada' }}</span>
                                            </h3>
                                        </p>
                                        <p>
                                            <strong>Sisa Antrian:</strong>
                                            <span id="queue-{{ $category->id }}">{{ $category->remaining_queues }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/js/pusher.min.js') }}"></script>
    <script src="{{ asset ('/js/callQueue.js') }}"></script>
    <script>
        // Konfigurasi Pusher
        const pusher = new Pusher('local-app-key', {
            cluster: 'mt1',
            wsHost: '192.168.100.102',
            wsPort: 6001,
            forceTLS: false,
            disableStats: true,
        });

        // Subscribe ke channel
        const channel = pusher.subscribe('queue-updates');

        // Bind event untuk real-time update
        channel.bind('App\\Events\\QueueUpdated', function (data) {
            console.log('Event diterima:', data);

            // Update jumlah sisa antrian
            const queueElement = document.getElementById(`queue-${data.categoryId}`);
            if (queueElement) {
                queueElement.innerText = data.remainingQueues;
            }

            // Update nomor panggilan utama hanya jika dari admin
            if (data.isAdminCall) {
                const currentNumberElement = document.getElementById('current-number');
                if (currentNumberElement) {
                    currentNumberElement.textContent = data.queueNumber || 'A-000';
                }

                // Update antrian terakhir yang dipanggil
                const lastQueueElement = document.getElementById(`last-queue-${data.categoryId}`);
                if (lastQueueElement) {
                    lastQueueElement.innerText = data.queueNumber || 'Belum Ada';
                }


            }
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


    </script>
</body>
</html>
