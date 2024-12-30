<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian</title>

    <!-- Link ke CSS Bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        .current-call {
            font-size: 4rem;
            font-weight: bold;
            color: #2980b9;
            text-align: center;
        }

        .left-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .category-title {
            font-size: 1rem;
            font-weight: bold;
        }

        .last-queue {
            font-size: 1.2rem;
            font-weight: bold;
            color: #34495e;
        }

        .remaining-queue {
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .video-container {
            width: 100%;
            height: auto;
            margin-bottom: 1rem;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 1rem;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .queue-section {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .queue-box {
            flex: 0 0 48%;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-5">
        <div class="row">
            <!-- Nomor Panggilan Saat Ini -->
            <div class="col-md-4 left-panel bg-primary text-white text-center">
                <div>
                    <h2 class="mb-4">Nomor Panggilan</h2>
                    <p id="current-number" class="current-call">A-001</p>
                </div>
            </div>

            <!-- Video dan Informasi Antrian -->
            <div class="col-md-8">
                @if ($video)
                    <div class="video-container">
                        <video controls autoplay loop style="width: 100%; max-height: 200px; border-radius: 10px;">
                            <source src="{{ asset('storage/' . $video->path) }}" type="video/mp4">
                            Browser Anda tidak mendukung video.
                        </video>
                    </div>
                @endif

                <h4 class="mb-3">Informasi Antrian</h4>

                <div class="queue-section">
                    @foreach ($categories as $category)
                    <div class="queue-box card" data-category-id="{{ $category->id }}">
                        <p class="category-title">{{ $category->name }}</p>
                        @if ($category->queues->isNotEmpty())
                            <p class="last-queue">Antrian Terakhir: {{ $category->queues->first()->number }}</p>
                            <p class="remaining-queue">Sisa Antrian: {{ $category->queues_count }}</p>
                        @else
                            <p class="last-queue">Antrian Terakhir: Belum Ada</p>
                            <p class="remaining-queue">Sisa Antrian: 0</p>
                        @endif
                    </div>
                    
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/pusher.min.js') }}"></script>
    <script>
        const pusher = new Pusher('local-app-key', {
            cluster: 'mt1',
            wsHost: '192.168.100.102',
            wsPort: 6001,
            forceTLS: false,
            disableStats: true,
        });

        const channel = pusher.subscribe('queue-updates');
        channel.bind('App\\Events\\QueueUpdated', function (data) {
            console.log('Event diterima:', data);
            if (data.queueNumber && data.isAdminCall) {
                document.getElementById('current-number').textContent = data.queueNumber;
            }
        });
    </script>
</body>
</html>
