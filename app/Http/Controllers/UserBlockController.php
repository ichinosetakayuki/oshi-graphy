<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Block;
use App\Models\Follow;
use GrahamCampbell\ResultType\Success;
use Illuminate\Validation\ValidationException;


class UserBlockController extends Controller
{
    /**
     * ユーザーをブロックする
     */
    public function store(Request $request, User $user)
    {
        if($request->user()->isBlocking($user)) {
            return response()->json([
                'ok' => true,
                'blocking' => true,
            ]);
        }

        if($request->user()->id === $user->id) {
            throw ValidationException::withMessages([
                'user' => '自分をブロックすることはできません。'
            ]);
        }

        // ブロックしたユーザーをフォローしていたら解除
        if($request->user()->isFollowing($user)) {
            Follow::where('follower_id', $request->user()->id)
                ->where('followed_id', $user->id)
                ->delete();
        }

        // ブロックしたユーザーにフォローされていたら、解除。
        if($user->isFollowing($request->user())) {
            Follow::where('follower_id', $user->id)
                ->where('followed_id', $request->user()->id)
                ->delete();
        }

        Block::firstOrCreate([
            'blocker_id' => $request->user()->id,
            'blocked_id' => $user->id,
        ]);

        return response()->json([
            'ok' => true,
            'blocking' => true,
            'message' => $user->name . 'さんをブロックしました。',
            'status_type' => 'success',
        ]);
    }

    /**
     * ユーザーのブロックを解除する
     */
    public function destroy(Request $request, User $user)
    {
        Block::where('blocker_id', $request->user()->id)
            ->where('blocked_id', $user->id)
            ->delete();

        return response()->json([
            'ok' => true,
            'blocking' => false,
            'message' => $user->name . 'さんのブロックを解除しました。',
            'status_type' => 'success',
        ]);
    }

    // ブロックユーザー一覧から複数人まとめてブロック解除するメソッド
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer',
        ]);

        $userIds = $request->input('user_ids', []);
        $length = count($userIds);
        
        Block::where('blocker_id', $request->user()->id)
            ->whereIn('blocked_id', $userIds)
            ->delete();

        return back()
            ->with('status', $length . '人のブロックを解除しました。')
            ->with('status_type', 'success');
    }

    /**
     * ブロックしているユーザーの一覧
     */
    public function blocks(Request $request)
    {
        $blocks = $request->user()->blocks()
            ->orderByPivot('created_at', 'desc')->paginate(20)->withQueryString();
        
        return view('user_block.blocks', compact('blocks'));
    }
}
