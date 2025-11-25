<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = ['blocker_id', 'blocked_id'];

    public function actor() // ブロックした側
    {
        return $this->belongsTo(User::class, 'blocker_id');
    }

    public function target() // ブロックされた側
    {
        return $this->belongsTo(User::class, 'blocked_id');
    }
}
