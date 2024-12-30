<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'path'];

    protected static function booted()
    {
        static::deleting(function ($video) {
            // Hapus file video dari penyimpanan
            if ($video->path && Storage::exists($video->path)) {
                Storage::delete($video->path);
            }
        });
    }
}
