<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    /** @use HasFactory<\Database\Factories\DiaryFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'artist_id', 'happened_on', 'body', 'is_public'];
    protected $casts = [
        'happened_on' => 'date',
        'is_public' => 'boolean'
    ]; // is_publicの値(0,1)をfalseやtrueに変換

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class)->withTrashed();
    }

    public function images()
    {
        return $this->hasMany(DiaryImage::class)->orderBy('id');
    }

    public function coverImage()
    {
        return $this->hasOne(DiaryImage::class)->oldestOfMany();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function likes()
    {
        return $this->hasMany(DiaryLike::class);
    }

    /**
     * この日記をそのユーザーがいいね済か？を調べる関数
     */
    public function likedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function likers()
    {
        return $this->belongsToMany(User::class, 'diary_likes')->withTimestamps();
    }
}
