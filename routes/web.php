<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Models\Queue;
use App\Models\Category;
use App\Events\QueueUpdated;

// Halaman utama untuk menampilkan antrian
Route::get('/', function () {
    $categories = Category::withCount(['queues' => function ($query) {
        $query->where('is_called', false); // Hanya hitung antrian yang belum dipanggil
    }])->get();

    return view('user.index', compact('categories'));
})->name('home');

Route::post('/ambil-antrian/{category}', function (Category $category) {
    $lastQueue = Queue::where('category_id', $category->id)->latest('id')->first();
    $nextNumber = $lastQueue ? intval(substr($lastQueue->number, strpos($lastQueue->number, '-') + 1)) + 1 : 1;

    $formattedNumber = $category->code . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

    $queue = Queue::create([
        'number' => $formattedNumber,
        'category_id' => $category->id,
    ]);

    $remainingQueues = Queue::where('category_id', $category->id)
        ->where('is_called', false)
        ->count();

    // Broadcast event
    event(new QueueUpdated($category->id, $remainingQueues));

    return response()->json([
        'success' => true,
        'queue' => $queue,
        'category_name' => $category->name,
        'remaining_queues' => $remainingQueues,
        'timestamp' => now()->format('Y-m-d H:i:s'),
    ]);
});