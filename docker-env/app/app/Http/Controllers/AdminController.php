<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
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
        $kw = trim($request->get('keyword', ''));

        $users = User::query()
            ->when($kw !== '', function ($q) use ($kw) {
                $q->where('username', 'like', "%{$kw}%");
            })
            ->withCount('posts')
            ->select('users.*')
            ->selectSub(function ($q) {
                $q->from('bookmarks')
                ->selectRaw('COUNT(*)')
                ->whereColumn('bookmarks.user_id', 'users.id');
            }, 'bookmarks_count')
            ->orderByDesc('id')
            ->paginate(20)
            ->appends($request->query());

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
            return view('admin.user_show', compact('user'));
        }

        public function showPost(Post $post)
        {
            $post->loadCount('bookmarks')->load(['user','comments.user']);
            return view('admin.post_show', compact('post'));
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

        return redirect()->route('user_list')->with('success', 'ユーザーを削除しました。');
    }

    public function destroyPost(Post $post)
    {
        if (method_exists($post, 'comments')) {
            $post->comments()->delete();
        }
        if (method_exists($post, 'bookmarks')) {
            $post->bookmarks()->delete();
        }

        if (!empty($post->image_path)) {
            Storage::disk('public')->delete($post->image_path);
        }
        $post->delete();

        return redirect()
            ->route('post_list')
            ->with('status', '投稿を削除しました。');
    }

}
