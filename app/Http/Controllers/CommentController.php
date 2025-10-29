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

        $validated = $request->validateWithBag('comment', [
            'body' => 'required|string|max:2000'
        ]);

        $diary->comments()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
            'parent_id' => null,
        ]);

        return back()->with('status', 'コメントを投稿しました。')->with('status_type', 'success');
    }

    public function reply(Request $request, Diary $diary)
    {
        if (!$diary->is_public && auth()->id() !== $diary->user_id) {
            return abort(403);
        }

        $validated = $request->validateWithBag('reply', [
            'body' => 'required|string|max:2000',
            // exists:comments,id コメントテーブルのidに存在する
            'parent_id' => 'required|integer|exists:comments,id',
        ]);

        $diary->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
            'parent_id' => $validated['parent_id'],
        ]);

        return back()->with('status', '返信を投稿しました')->with('status_type', 'success');
    }

    public function destroy(Comment $comment)
    {
        if(auth()->id() !== $comment->user_id) {
            abort(403);
        }

        $comment->delete();

        return back()->with('status', 'コメントを削除しました。')->with('status_type', 'success');
    }
}
