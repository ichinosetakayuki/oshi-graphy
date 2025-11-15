<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable = ['follower_id', 'followed_id'];

    public function actor() // フォローした側
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function target() // フォローされた側
    {
        return $this->belongsTo(User::class, 'followed_id');
    }
}
