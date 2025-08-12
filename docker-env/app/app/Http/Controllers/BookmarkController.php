<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookmarkController extends Controller
{

    public function toggle(Request $request, $id)
    {
        $type = $request->route('type', 'post');

        $userId = $request->user()->id;
        $column = $type === 'product' ? 'product_id' : 'post_id';

        $q = DB::table('bookmarks')
            ->where('user_id', $userId)
            ->where($column, $id);

        if ($q->exists()) {
            $q->delete();
            $bookmarked = false;
        } else {
            DB::table('bookmarks')->insert([
                'user_id'    => $userId,
                $column      => $id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $bookmarked = true;
        }

        return back()->with('status', $bookmarked ? 'ブックマークしました' : 'ブックマークを外しました');
    }

    public function index(Request $request)
    {
        $userId = \Auth::id();

        $bookmarks = \DB::table('bookmarks')
            ->leftJoin('posts', 'bookmarks.post_id', '=', 'posts.id')
            ->leftJoin('products', 'bookmarks.product_id', '=', 'products.id')
            ->where('bookmarks.user_id', $userId)
            ->orderByDesc('bookmarks.created_at')
            ->select([
                'bookmarks.id as bookmark_id',
                'bookmarks.created_at as bookmarked_at',
                'posts.id as post_id',
                'posts.title as post_title',
                'posts.image_path as post_image',
                'posts.user_id as post_user_id',
                'products.id as product_id',
                'products.title as product_title',
                'products.file_path as product_file',
                'products.user_id as product_user_id',
            ])
            ->paginate(12);

        return view('bookmark_list', compact('bookmarks')); // ← ここを items ではなく bookmarks で返す
    }

}
