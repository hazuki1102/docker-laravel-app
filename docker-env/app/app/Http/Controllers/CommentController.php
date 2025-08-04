<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|max:255',
        ]);

        Comment::create([
            'post_id' => $id,
            'user_id' => Auth::id(),
            'body' => $request->input('comment'),
        ]);

        return redirect()->back()->with('success', 'コメントを投稿しました。');
    }

}
