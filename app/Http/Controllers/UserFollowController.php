<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;


class UserFollowController extends Controller
{
    /**
     * ユーザーフォロー情報を保存
     */
    public function store(Request $request, User $user)
    {
        Gate::authorize('follow', $user);

        // すでにフォロー済みならOKで返す
        if($request->user()->isFollowing($user)) {
            return response()->json([
                'ok' => true,
                'following' => true,
                'followers_count' => $user->followers()->count(),
            ]);
        }

        if($request->user()->id === $user->id) {
            throw ValidationException::withMessages([
                'user' => '自分をフォローすることはできません'
            ]);
        }

        Follow::firstOrCreate([
            'follower_id' => $request->user()->id,
            'followed_id' => $user->id,
        ]);

        return response()->json([
            'ok' => true,
            'following' => true,
            'followers_count' => $user->followers()->count(),
            'message' => $user->name . 'さんをフォローしました。',
            'status_type' => 'success',
            ]);
    }

    public function destroy(Request $request, User $user)
    {
        Gate::authorize('unfollow', $user);

        Follow::where('follower_id', $request->user()->id)
            ->where('followed_id', $user->id)
            ->delete();

        return response()->json([
            'ok' => true,
            'following' => false,
            'followers_count' => $user->followers()->count(),
            'message' => $user->name . 'さんのフォローを解除しました。',
            'status_type' => 'success',
            ]);
    }
}
