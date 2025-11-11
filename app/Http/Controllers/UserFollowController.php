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

        // フラッシュメッセージも渡す（toastで表示用）
        return response()->json([
            'ok' => true,
            'following' => true,
            'followings_count' => $user->followings()->count(),
            'followers_count' => $user->followers()->count(),
            'message' => $user->name . 'さんをフォローしました。', // フラッシュメッセージ
            'status_type' => 'success',
            ]);
    }

    /**
     * フォロー解除
     */
    public function destroy(Request $request, User $user)
    {
        Gate::authorize('unfollow', $user);

        Follow::where('follower_id', $request->user()->id)
            ->where('followed_id', $user->id)
            ->delete();

        // フラッシュメッセージも渡す（toastで表示用）
        return response()->json([
            'ok' => true,
            'following' => false,
            'followings_count' => $user->followings()->count(),
            'followers_count' => $user->followers()->count(),
            'message' => $user->name . 'さんのフォローを解除しました。', // フラッシュメッセージ
            'status_type' => 'success',
            ]);
    }

    public function followers(Request $request)
    {
        $followers = $request->user()->followers()
            ->orderByPivot('created_at', 'desc')->paginate(20)->withQueryString();

        return view(('user_follow.followers'), compact('followers'));
    }
}
