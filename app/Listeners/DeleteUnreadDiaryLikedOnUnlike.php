<?php

namespace App\Listeners;

use App\Events\LikeRemoved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\DiaryLiked;
use App\Models\Diary;

class DeleteUnreadDiaryLikedOnUnlike
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LikeRemoved $event): void
    {
        $like = $event->like;
        if(!$like->likeable instanceof Diary) return;

        $diary = $like->likeable;
        $actor = $like->user;

        if(!$diary || !$actor) return;

        $diary->user->notifications()
            ->whereNull('read_at') //未読通知だけに限定
            ->where('type', DiaryLiked::class) // いいね通知だけに限定
            ->where('data->diary_id', $diary->id) // notifications.dataはJSONカラム。JSONパスで同じ日記の通知に限定
            ->where('data->actor_user_id', $actor->id) // 誰が押したか（actor）でさらに限定
            ->delete(); // 以上の条件に合う通知を削除。
    }
}
