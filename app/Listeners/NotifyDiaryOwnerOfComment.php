<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\CommentPostedOnYourDiary;
use App\Notifications\CommentOrReplyNotification;
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
        // $owner = $diary->user;
        $owner = $comment->parent?->user ?? $diary->user;
        $actor = $comment->user;

        if($owner && $actor && $owner->is($actor)) return; 

        $isReply = (bool)$comment->parent_id;

        $targetExcerpt = $isReply
            ? mb_strimwidth((string)$comment->parent?->body ?? '', 0, 40, '...')
            : mb_strimwidth((string)$diary->body, 0, 40, '...');

        $payload = [
            'diaryId' => $diary->id,
            'commentId' => $comment->id,
            'actorUserId' => $actor?->id,
            'actorName' => $actor?->name ?? '退会ユーザー',
            'isReply' => $isReply,
            'excerpt' => $targetExcerpt,
        ];

        /**
         * DB トランザクションがロールバックしたのに通知だけ飛ぶ事故を避けるため、
         * コミット後（永続化が確定してから）通知を送る。
         * sync でも “同じリクエスト内のコミット後” に実行される。
         */
        DB::afterCommit(function () use ($owner, $payload) {
            $owner?->notify(new CommentOrReplyNotification(
                diaryId: $payload['diaryId'],
                commentId: $payload['commentId'],
                actorUserId: $payload['actorUserId'],
                actorName: $payload['actorName'],
                isReply: $payload['isReply'],
                excerpt: $payload['excerpt']
            ));
        });
    }
}
