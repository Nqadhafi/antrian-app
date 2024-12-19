<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</body>
<script>
    
</script>
</html>
