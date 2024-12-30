<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Video;

class DisplayController extends Controller
{
    public function index()
    {
        $categories = Category::with(['queues' => function ($query) {
            $query->latest('id')->limit(1); // Ambil nomor antrian terakhir
        }])->get();

        $video = Video::latest('id')->first(); // Ambil video terbaru dari database

        return view('user.display', compact('categories', 'video'));
    }
}
