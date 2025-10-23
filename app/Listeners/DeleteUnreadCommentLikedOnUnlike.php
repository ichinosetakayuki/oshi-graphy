<?php

namespace App\Listeners;

use App\Events\CommentLikeRemoved;
use App\Notifications\CommentLiked;
use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DeleteUnreadCommentLikedOnUnlike
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
    public function handle(CommentLikeRemoved $event): void
    {
        $like = $event->like;
        if(!$like->likeable instanceof Comment) return;

        $comment = $like->likeable;
        $actor = $like->user;

        if(!$comment || $actor) return;

        $comment->user->notifications()
            ->whereNull('read_at')
            ->where('type', CommentLiked::class)
            ->where('data->comment_id', $comment->id)
            ->where('data->actor_user_id', $actor->id)
            ->delete();
        
    }
}
