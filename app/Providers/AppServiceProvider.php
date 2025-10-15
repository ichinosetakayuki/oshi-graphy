<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Artist;
use App\Models\Comment;
use App\Policies\ArtistPolicy;
use App\Observers\CommentObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * アプリの起動時に毎回実行される。
     */
    public function boot(): void
    {
        // Gateの定義。Gate::define('能力名', 関数)で「誰がその能力を持つか」を宣言。
        Gate::define('access-admin', fn($user) => (bool) $user->is_admin);

        // Policyの登録。ArtistモデルとArtistPolicyの対応づけ。
        Gate::policy(Artist::class, ArtistPolicy::class);

        // CommentモデルにObserverを紐づける登録処理
        Comment::observe(CommentObserver::class);
    }
}
