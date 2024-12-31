<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    // Accessor untuk Antrian Terakhir yang Dipanggil
    public function getLastQueueAttribute()
    {
        return $this->queues()
            ->where('is_called', true)
            ->orderBy('created_at', 'desc')
            ->first()?->number;
    }

    // Accessor untuk Sisa Antrian
    public function getRemainingQueuesAttribute()
    {
        return $this->queues()
            ->where('is_called', false)
            ->count();
    }
}
