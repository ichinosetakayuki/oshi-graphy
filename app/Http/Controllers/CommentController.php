<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diary;
use App\Models\Comment;


class CommentController extends Controller
{
    public function store(Request $request, Diary $diary)
    {
        if(!$diary->is_public && auth()->id() !== $diary->user_id) {
            return abort(403);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:2000'
        ]);

        $diary->comments()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        return back()->with('status', 'コメントを投稿しました。');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('status', 'コメントを削除しました。');
    }
}
