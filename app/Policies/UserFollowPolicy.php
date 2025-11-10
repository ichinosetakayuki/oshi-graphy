<?php

namespace App\Policies;

use App\Models\User;

class UserFollowPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    // フォロー可能か
    public function follow(User $actor, User $target): bool
    {
        if($actor->id === $target->id) return false; // 自己フォロー禁止
        return true;
    }

    // アンフォロー可能か（＝自分がフォローしていたらOK）
    public function unfollow(User $actor, User $target): bool
    {
        if($actor->id === $target->id) return false;
        return $actor->isFollowing($target);
    }
}
