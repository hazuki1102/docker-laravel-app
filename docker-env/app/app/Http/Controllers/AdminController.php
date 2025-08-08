<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Post;
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
        $user->loadCount(['posts', 'bookmarks'])
            ->load(['posts' => function($query) {
                $query->orderBy('created_at', 'desc');
            }]);

        return view('user_show', compact('user'));
    }

    public function showPost(Post $post)
    {
        $post->loadCount('bookmarks')
            ->load(['user', 'comments.user']);

        return view('post_show', compact('post'));
    }


}
