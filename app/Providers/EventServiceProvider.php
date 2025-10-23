<?php

namespace App\Providers;

use App\Events\CommentCreated;
use App\Events\LikeAdded;
use App\Events\LikeRemoved;
use App\Events\CommentLikeAdded;
use App\Events\CommentLikeRemoved;
use App\Listeners\DeleteUnreadCommentLikedOnUnlike;
use App\Listeners\NotifyDiaryOwnerOfComment;
use App\Listeners\DeleteUnreadDiaryLikedOnUnlike;
use App\Listeners\SendCommentLikedNotification;
use App\Listeners\SendDiaryLikedNotification;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CommentCreated::class => [
            NotifyDiaryOwnerOfComment::class,
        ],
        LikeAdded::class => [
            SendDiaryLikedNotification::class,
        ],
        LikeRemoved::class => [
            DeleteUnreadDiaryLikedOnUnlike::class,
        ],
        CommentLikeAdded::class => [
            SendCommentLikedNotification::class,
        ],
        CommentLikeRemoved::class => [
            DeleteUnreadCommentLikedOnUnlike::class,
        ],
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
