<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Post;
use App\Models\Product;
use App\Models\Comment;
use App\Models\Bookmark;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
        public function index()
    {
        return view('ownerpage');
    }

    public function userList(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = User::withCount(['posts', 'bookmarks']);

        if (!empty($keyword)) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        $users = $query->paginate(10);

        return view('user_list', compact('users'));
    }

    public function postList(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = Post::with('user')->withCount('bookmarks');

        if (!empty($keyword)) {
            $query->where('title', 'like', "%{$keyword}%");
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('post_list', compact('posts'));
    }

        public function showUser(User $user)
        {
            $user->loadCount(['posts','bookmarks'])
                ->load(['posts' => fn($q) => $q->latest()]);
            return view('user_show', compact('user'));
        }

        public function showPost(Post $post)
        {
            $post->loadCount('bookmarks')->load(['user','comments.user']);
            return view('post_show', compact('post'));
        }
    public function destroyUser(User $user)
    {
        if (auth()->id() === $user->id) {
            abort(403, '自分自身は削除できません。');
        }
        if ($user->is_admin) {
            abort(403, '管理者アカウントは削除できません。');
        }

        DB::transaction(function () use ($user) {
            Post::where('user_id', $user->id)->delete();
            Product::where('user_id', $user->id)->delete();
            Comment::where('user_id', $user->id)->delete();
            Bookmark::where('user_id', $user->id)->delete();

            $user->delete();
        });

        return redirect()->route('user.list')->with('success', 'ユーザーを削除しました。');
    }

}
