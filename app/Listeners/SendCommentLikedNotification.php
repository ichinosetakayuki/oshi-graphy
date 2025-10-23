<?php

namespace App\Listeners;

use App\Events\CommentLikeAdded;
use App\Models\Comment;
use App\Notifications\CommentLiked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCommentLikedNotification
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
    public function handle(CommentLikeAdded $event): void
    {
        $like = $event->like;
        if(!$like->likeable instanceof Comment) return;

        $comment = $like->likeable;
        $actor = $like->user;

        if((int)$comment->user_id === (int)$actor->id) return;

        $comment->user->notifications()
            ->whereNull('read_at') //未読通知だけに限定
            ->where('type', CommentLiked::class) // いいね通知だけに限定
            ->where('data->comment_id', $comment->id) // notifications.dataはJSONカラム。JSONパスで同じ日記の通知に限定
            ->where('data->actor_user_id', $actor->id) // 誰が押したか（actor）でさらに限定
            ->delete();

        $excerpt = mb_strimwidth($comment->body, 0, 40, '...');
        $comment->user->notify(new CommentLiked($actor, $comment, $excerpt));

    }
}
