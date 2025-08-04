<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
        public function store($id)
    {
        $user = Auth::user();
        $post = Post::findOrFail($id);

        if (!$user->bookmarks()->where('post_id', $post->id)->exists()) {
            $user->bookmarks()->attach($post->id);
        }

        return redirect()->back()->with('success', 'ブックマークしました！');
    }

    public function index()
    {
        $user = Auth::user();
        $bookmarks = $user->bookmarks()->with('user')->orderBy('created_at', 'desc')->paginate(12);

        return view('bookmark_list', compact('bookmarks'));
    }

}
