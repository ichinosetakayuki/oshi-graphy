<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    /** @use HasFactory<\Database\Factories\DiaryFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'artist_id', 'happened_on', 'body', 'is_public'];
    protected $casts = ['is_public' => 'boolean']; // is_publicの値(0,1)をfalseやtrueに変換

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class)->withTrashed();
    }
}
