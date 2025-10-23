<?php

namespace App\Observers;

use App\Models\Like;
use App\Models\Comment;
use App\Events\CommentLikeAdded;
use App\Events\CommentLikeRemoved;


class LikeObserver
{
    /**
     * Handle the Like "created" event.
     */
    public function created(Like $like): void
    {
        if($like->likeable instanceof Comment) {
            CommentLikeAdded::dispatch($like);
        }
    }

    /**
     * Handle the Like "updated" event.
     */
    public function updated(Like $like): void
    {
        //
    }

    /**
     * Handle the Like "deleted" event.
     */
    public function deleted(Like $like): void
    {
        if($like->likeable instanceof Comment) {
            CommentLikeRemoved::dispatch($like);
        }
    }

    /**
     * Handle the Like "restored" event.
     */
    public function restored(Like $like): void
    {
        //
    }

    /**
     * Handle the Like "force deleted" event.
     */
    public function forceDeleted(Like $like): void
    {
        //
    }
}
