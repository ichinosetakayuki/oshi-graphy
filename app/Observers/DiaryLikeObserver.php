<?php

namespace App\Observers;

use App\Models\DiaryLike;
use App\Events\LikeAdded;
use App\Events\LikeRemoved;

class DiaryLikeObserver
{
    /**
     * Handle the DiaryLike "created" event.
     */
    public function created(DiaryLike $like): void
    {
        // LikeAdded::dispatch($like);
    }

    /**
     * Handle the DiaryLike "updated" event.
     */
    public function updated(DiaryLike $diaryLike): void
    {
        //
    }

    /**
     * Handle the DiaryLike "deleted" event.
     */
    public function deleted(DiaryLike $like): void
    {
        // LikeRemoved::dispatch($like);
    }

    /**
     * Handle the DiaryLike "restored" event.
     */
    public function restored(DiaryLike $diaryLike): void
    {
        //
    }

    /**
     * Handle the DiaryLike "force deleted" event.
     */
    public function forceDeleted(DiaryLike $diaryLike): void
    {
        //
    }
}
