<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\CommentPostedOnYourDiary;
use Illuminate\Support\Facades\DB;


class NotifyDiaryOwnerOfComment implements ShouldQueue
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
    public function handle(CommentCreated $event): void
    {
        $comment = $event->comment;
        $diary = $comment->diary;
        $owner = $diary->user;
        $actor = $comment->user;

        if($owner && $actor && $owner->is($actor)) return; 

        $payload = [
            'diaryId' => $diary->id,
            'commentId' => $comment->id,
            'actorUserId' => $actor?->id,
            'actorName' => $actor?->name ?? '退会ユーザー',
            'excerpt' => mb_strimwidth($diary->body, 0, 40, '...')
        ];

        /**
         * DB トランザクションがロールバックしたのに通知だけ飛ぶ事故を避けるため、
         * コミット後（永続化が確定してから）通知を送る。
         * sync でも “同じリクエスト内のコミット後” に実行される。
         */
        DB::afterCommit(function () use ($owner, $payload) {
            $owner?->notify(new CommentPostedOnYourDiary(
                diaryId: $payload['diaryId'],
                commentId: $payload['commentId'],
                actorUserId: $payload['actorUserId'],
                actorName: $payload['actorName'],
                excerpt: $payload['excerpt']
            ));
        });
    }
}
