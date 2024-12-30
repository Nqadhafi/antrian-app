<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Category;
use App\Events\QueueUpdated;

class QueueController extends Controller
{
    /**
     * Tampilkan halaman utama dengan daftar kategori.
     */
    public function index()
    {
        $categories = Category::withCount(['queues' => function ($query) {
            $query->where('is_called', false); // Hanya hitung antrian yang belum dipanggil
        }])->get();

        return view('user.index', compact('categories'));
    }

    /**
     * Proses pengambilan nomor antrian untuk kategori tertentu.
     */
    public function store(Request $request, Category $category)
    {
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

        // Broadcast event dengan nama kategori dan nomor antrian
        event(new QueueUpdated(
            $category->id,
            $remainingQueues,
            $category->name,
            $formattedNumber
        ));

        return response()->json([
            'success' => true,
            'queue' => $queue,
            'category_name' => $category->name,
            'remaining_queues' => $remainingQueues,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ]);
    }
}
