<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $user = $request->user();

        $existing = Like::where('user_id', $user->id)->where('post_id', $post->id)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            Like::create(['user_id' => $user->id, 'post_id' => $post->id]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes()->count(),
            'post_id' => $post->id,
        ]);
    }

    public function index()
    {
        $posts = \App\Models\Post::with('user')
            ->withCount('likes')
            ->orderByDesc('created_at')
            ->paginate(20);

        $posts->getCollection()->transform(function ($p) {
            $p->type = 'post';
            return $p;
        });

        $likedPostIds = auth()->check()
            ? auth()->user()->likedPosts()->pluck('posts.id')->toArray()
            : [];

        $likedProductIds = [];

        return view('home', [
            'posts'            => $posts,
            'likedPostIds'     => $likedPostIds,
            'likedProductIds'  => $likedProductIds,
        ]);
    }


}
