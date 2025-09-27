<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaryLike extends Model
{
    protected $fillable = ['diary_id', 'user_id'];

    public function diary()
    {
        return $this->belongsTo(Diary::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
