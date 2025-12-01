<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * ブロックされたユーザーはブロックしたユーザープロフィールを見ることができない
     */
    public function view(User $currentUser, User $profileUser)
    {
        // プロフィールの持ち主が、見ようとしているユーザーをブロックしているか？
        return !$profileUser->blocks()->where('users.id', $currentUser->id)->exists();
    }

    /**
     * フォロー可能か
     */
    public function follow(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->id === $targetUser->id) return false; // 自己フォロー禁止
        return true;
    }

    /**
     * アンフォロー可能か（＝自分がフォローしていたらOK）
     */
    public function unfollow(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->id === $targetUser->id) return false;
        return $currentUser->isFollowing($targetUser);
    }
}
