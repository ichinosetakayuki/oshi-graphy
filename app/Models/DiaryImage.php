<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaryImage extends Model
{
    /** @use HasFactory<\Database\Factories\DiaryImageFactory> */
    use HasFactory;

    protected $fillable = ['diary_id', 'path'];

    public function diary()
    {
        return $this->belongsTo(Diary::class);
    }
}
