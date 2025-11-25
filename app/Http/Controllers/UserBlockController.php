<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Block;
use App\Models\Follow;
use GrahamCampbell\ResultType\Success;

class UserBlockController extends Controller
{
    public function store(Request $request, User $target)
    {
        if($request->user()->isBlocking($target)) {
            return response()->json([
                'ok' => true,
                'blocking' => true,
            ]);
        }

        Block::firstOrCreate([
            'blocker_id' => $request->user()->id,
            'blocked_id' => $target->id,
        ]);

        // ブロックしたユーザーにフォローされていたら、解除。
        if($target->isFollowing($request->user())) {
            Follow::where('follower_id', $target->id)
                ->where('followed_id', $request->user()->id)
                ->delete();
        }

        return response()->json([
            'ok' => true,
            'blocking' => true,
            'message' => $target->name . 'さんをブロックしました。',
            'status_type' => 'success',
        ]);
    }

    public function destroy(Request $request, User $target)
    {
        Block::where('blocker_id', $request->user()->id)
            ->where('blocked_id', $target->id)
            ->delete();

        return response()->json([
            'ok' => true,
            'blocking' => false,
            'message' => $target->name . 'さんのブロックを解除しました。',
            'status_type' => 'success',
        ]);
    }
}
