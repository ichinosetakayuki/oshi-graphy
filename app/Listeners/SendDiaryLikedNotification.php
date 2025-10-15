<?php

namespace App\Listeners;

use App\Events\LikeAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\DiaryLiked;

class SendDiaryLikedNotification
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
    public function handle(LikeAdded $event): void
    {
        $like = $event->like->loadMissing(['diary', 'user']);
        $diary = $like->diary;
        $actor = $like->user;

        if(!$diary || !$actor) return;
        if($diary->user_id === $actor->id) return; // 自分には通知しない

        $diary->user->notifications()
            ->whereNull('read_at') //未読通知だけに限定
            ->where('type', DiaryLiked::class) // いいね通知だけに限定
            ->where('data->diary_id', $diary->id) // notifications.dataはJSONカラム。JSONパスで同じ日記の通知に限定
            ->where('data->actor_user_id', $actor->id) // 誰が押したか（actor）でさらに限定
            ->delete(); // 以上の条件に合う重複通知を丸ごと削除。結果、新規通知だけが１件残る。

        // 本文を丸める。excerpt:引用
        $excerpt = mb_strimwidth($diary->body, 0, 40, '...');
        // 新しい通知を作成
        $diary->user->notify(new DiaryLiked(
            diaryId: $diary->id,
            actorUserId: $actor->id,
            actorName: $actor->name,
            excerpt: $excerpt
        ));
    }
}
