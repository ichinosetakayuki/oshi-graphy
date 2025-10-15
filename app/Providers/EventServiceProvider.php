<?php

namespace App\Providers;

use App\Events\CommentCreated;
use App\Events\LikeAdded;
use App\Events\LikeRemoved;
use App\Listeners\NotifyDiaryOwnerOfComment;
use App\Listeners\DeleteUnreadDiaryLikedOnUnlike;
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
