<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $data = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
            'body' => strip_tags($data['comment']),
        ]);

        return back()->with('success', 'コメントを投稿しました。');
    }
}
