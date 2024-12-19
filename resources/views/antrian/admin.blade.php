<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Antrian Percetakan</title>
</head>
<body>
    <h1>Admin - Antrian Percetakan</h1>
    @foreach($categories as $category)
        <h2>{{ $category->name }}</h2>
        <form action="/admin/panggil-antrian" method="POST">
            @csrf
            <input type="hidden" name="category_id" value="{{ $category->id }}">
            <button type="submit">Panggil Antrian Berikutnya</button>
        </form>
    @endforeach

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @elseif(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
</body>
</html>
