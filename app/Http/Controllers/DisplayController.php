<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Queue;
use App\Models\Video;

class DisplayController extends Controller
{
    public function index()
    {
        $categories = Category::with('queues')->get();
        $video = Video::latest('id')->first();

        return view('user.display', compact('categories', 'video'));
    }

    public function callQueue(Request $request)
    {
        $queue = Queue::findOrFail($request->queue_id);

        // Update status antrian
        $queue->is_called = true;
        $queue->save();

        // Broadcast event (untuk real-time update)
        broadcast(new \App\Events\QueueUpdated($queue->category_id, $queue->number, $queue->category->remaining_queues,$queue->is));

        return response()->json(['success' => true, 'queue' => $queue]);
    }
}
