<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserFollowed;
use App\Notifications\UserFollowedNotification;

class SendUserFollowedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {}

    /**
     * Handle the event.
     */
    public function handle(UserFollowed $event): void
    {
        $follow = $event->follow;
        $actor = $follow->actor;
        $target = $follow->target;

        $target->notifications()
            ->whereNull('read_at')
            ->where('type', UserFollowedNotification::class)
            ->where('data->actor_user_id', $actor->id)
            ->where('data->target_user_id', $target->id)
            ->delete();

        $target->notify(new UserFollowedNotification($actor,$target));

    }
}
