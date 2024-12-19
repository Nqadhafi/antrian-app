<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'category_id', 'is_called', 'is_printed'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
