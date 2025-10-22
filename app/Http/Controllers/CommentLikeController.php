<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentLikeController extends Controller
{
    public function store(Request $request, Comment $comment)
    {
        $like = $comment->likes()->firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        // 自分のコメント以外なら通知
        // if($comment->user_id !== $request->user()->id) {
        //     $comment->user->notify()
        // }

        return response()->json([
            'ok' => true,
            'liked' => true,
            'count' => $comment->likes()->count(),
        ]);
    }

    public function destroy(Request $request, Comment $comment)
    {
        $like = $comment->likes()->where('user_id', $request->user()->id)->first();
        if($like) {
            $like->delete();
        }

        return response()->json([
            'ok' => true,
            'liked' => false,
            'count' => $comment->likes()->count(),
        ]);
    }
}
