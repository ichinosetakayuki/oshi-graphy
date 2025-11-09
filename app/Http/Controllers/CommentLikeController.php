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

    public function commentLikers(Comment $comment)
    {
        $likers = $comment->likers()
            ->orderByPivot('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $comment->load('diary.user');

        return view('comments.likes.index', compact('comment', 'likers'));
    }
}
