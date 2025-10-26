<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];
    // $castsは特定の型に自動変換する。is_adminを数値0or1からtrue/falseへ

    protected $fillable = [
        'name',
        'email',
        'password',
        'icon_path',
        'profile',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function diaries()
    {
        return $this->hasMany(Diary::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    protected $appends = ['icon_url'];

    public function getIconUrlAttribute(): string
    {
        return $this->icon_path
            ? asset('storage/'.$this->icon_path)
            : asset('images/icon_placeholder.png');
    }

    /**
     * このユーザーがつけた「いいね」の一覧
     * 戻り値はDiaryLikeのモデルコレクション
     */
    // public function diaryLikes()
    // {
    //     return $this->hasMany(DiaryLike::class);
    // }

    // public function likedDiaries()
    // {
    //     return $this->belongsToMany(Diary::class, 'diary_likes')->withTimestamps();
    // }

    /**
     * Userモデルの起動フック。
     * ユーザー削除前（deleting）のイベントリスナーを登録し、
     * 1) ユーザーのアイコンファイルを削除し、
     * 2) 所有する日記を Eloquent 経由で chunk 削除（→ Diary::deleting が発火し写真も物理削除）
     * する前処理をセットアップする。
     */
    protected static function booted(): void
    {
        static::deleting(function(User $user) {

            // ユーザーアイコンの物理削除
            if(!empty($user->icon_path)) {
                Storage::disk('public')->delete($user->icon_path);
            }

            // chunkById:ID順に100件ずつとりだして処理する関数
            $user->diaries()->select('id')->chunkById(100, function($chunk){
                $chunk->each->delete(); // それぞれを削除
            });
        });
    }
}
