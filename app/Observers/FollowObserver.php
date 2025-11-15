<?php

namespace App\Observers;

use App\Events\UserFollowed;
use App\Models\Follow;

class FollowObserver
{
    public function created(Follow $follow): void
    {
        UserFollowed::dispatch($follow);
    }
}
