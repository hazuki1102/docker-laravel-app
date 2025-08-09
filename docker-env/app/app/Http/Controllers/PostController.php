<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function index()
        {
            $posts = Post::with('user')
                ->select('id', 'user_id', 'title', 'image_path', 'created_at')
                ->get()
                ->each(function ($item) {
                    $item->type = 'post';
                });

            $products = Product::with('user')
                ->select('id', 'user_id', 'title', DB::raw("file_path as image_path"), 'created_at')
                ->get()
                ->each(function ($item) {
                    $item->type = 'product';
                });

            $merged = $posts->merge($products)->sortByDesc('created_at')->values();


            // ページネーション（10件ごと）
            $currentPage = request()->input('page', 1);
            $perPage = 10;
            $paged = $merged->slice(($currentPage - 1) * $perPage, $perPage)->values();
            $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $paged,
                $merged->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );

            return view('home', ['posts' => $paginator]);
        }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'temp_image_path' => 'required|string',
            'title' => 'required|string|max:30',
            'body' => 'nullable|string',
            'hashtags' => 'nullable|string',
            'materials' => 'nullable|string',
        ]);

        $tempImagePath = $request->input('temp_image_path');

        if (!$tempImagePath || !Storage::exists($tempImagePath)) {
            return redirect()->route('posts.create')->withErrors(['image' => '画像が見つかりません。再度アップロードしてください。']);
        }

        $newPath = 'public/images/' . basename($tempImagePath);
        Storage::move($tempImagePath, $newPath);
        $dbPath = str_replace('public/', '', $newPath);

        Post::create([
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'body' => $request->input('body', ''),
            'hashtags' => $request->input('hashtags', ''),
            'materials' => $request->input('materials', ''),
            'image_path' => $dbPath,
        ]);

        return redirect()->route('posts.index')->with('success', '投稿が完了しました。');
    }

    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:10240',
            'title' => 'required|string|max:30',
            'body' => 'nullable|string',
            'hashtags' => 'nullable|string',
            'materials' => 'nullable|string',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $tempPath = $request->file('image')->store('tmp');
            $validated['temp_image_path'] = $tempPath;
        } else {
            return back()->withErrors(['image' => '画像のアップロードに失敗しました。'])->withInput();
        }

        return view('posts.confirm', ['data' => $validated]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::with(['comments.user'])->findOrFail($id);
        return view('post_detail', compact('post'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);

        if (auth()->id() !== $post->user_id) {
            return redirect()->route('posts.show', $id);
        }

        return view('edit.post_edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'profile' => 'nullable|string|max:1000',
            'icon_path' => 'nullable|string',
        ]);

        if (!empty($validated['icon_path'])) {
            $from = $validated['icon_path'];
            $to   = 'icons/' . basename($from);
            Storage::disk('public')->move($from, $to);
            $user->icon_path = $to;
        }

        $user->username = $validated['username'] ?? $user->username;
        $user->email    = $validated['email'] ?? $user->email;
        $user->profile  = $validated['profile'] ?? null;

        if (!empty($validated['icon_path'])) {
            $user->icon_path = $validated['icon_path'];
        }

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()->route('mypage')->with('success', 'プロフィールを更新しました。');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function mypage()
    {
        $user = Auth::user();

        $posts = Post::where('user_id', $user->id)->latest()->paginate(5);

        return view('mypage', compact('user', 'posts'));
    }

    public function select()
    {
        return view('create.create_select');
    }
    public function post()
    {
        return view('create.create_post');
    }
    public function product()
    {
        return view('create.create_product');
    }

    public function postConf(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:10240',
            'title' => 'required|string|max:30',
            'body' => 'nullable|string',
            'hashtags' => 'nullable|string',
            'materials' => 'nullable|string',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $tempPath = $request->file('image')->store('tmp');

            $imageData = base64_encode(Storage::get($tempPath));
            $mimeType = Storage::mimeType($tempPath);

            $validated['temp_image_path'] = $tempPath;
            $validated['image_base64'] = "data:$mimeType;base64,$imageData";
        } else {
            return back()->withErrors(['image' => '画像のアップロードに失敗しました。'])->withInput();
        }

        return view('create.create_post_conf', ['data' => $validated]);
    }

        public function productConf(Request $request)
        {
            $validated = $request->validate([
                'file' => 'required|file|max:10240',
                'title' => 'required|string|max:30',
                'caption' => 'nullable|string',
                'tags' => 'nullable|string|max:255',
                'price' => 'required|integer|min:0',
            ]);

            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $tempPath = $request->file('file')->store('tmp');
            } else {
                return back()->withErrors(['file' => 'ファイルのアップロードに失敗しました。'])->withInput();
            }

            $extension = strtolower($request->file('file')->getClientOriginalExtension());
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);

            $productData = [
                'file_path' => $tempPath,
                'title' => $validated['title'],
                'caption' => $validated['caption'] ?? null,
                'tags' => $validated['tags'] ?? null,
                'price' => $validated['price'],
            ];

            if ($isImage) {
                $imageData = base64_encode(Storage::get($tempPath));
                $mimeType = Storage::mimeType($tempPath);
                $productData['image_base64'] = "data:$mimeType;base64,$imageData";
            }

            $request->session()->put('product_data', $productData);

            return view('create.create_product_conf', ['data' => $productData]);
        }


    public function productStore(Request $request)
    {
        $data = $request->session()->get('product_data');

        if (!$data || !isset($data['file_path']) || !Storage::exists($data['file_path'])) {
            return redirect()->route('create.create_product')->withErrors(['file' => '一時ファイルが見つかりません。再アップロードしてください。']);
        }

        $filename = basename($data['file_path']);
        $finalPath = 'public/products/' . $filename;
        Storage::move($data['file_path'], $finalPath);

        Product::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'price' => $data['price'],
            'file_path' => 'products/' . $filename,
            'post_id' => null,
        ]);

        $request->session()->forget('product_data');

        return redirect()->route('mypage')->with('success', '素材の投稿が完了しました。');
    }


        public function editConf(Request $request)
        {
            $validated = $request->validate([
                'username' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'password' => 'nullable|string|min:8|confirmed',
                'profile' => 'nullable|string|max:1000',
                'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('icon') && $request->file('icon')->isValid()) {
                $tmpPath = $request->file('icon')->store('tmp/icons', 'public');
                $validated['icon_path'] = $tmpPath;
            }

            $validated['current_icon_path'] = optional(\Auth::user())->icon_path;

            return view('user.edit_conf', ['data' => $validated]);
        }


    public function deleteConf()
    {
        $user = Auth::user();
        return view('user.delete_conf', compact('user'));
    }
    public function deletePostConf($id)
    {
        $post = Post::findOrFail($id);

        return view('edit.post_delete_conf', compact('post'));
    }


    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        if (!empty($keyword)) {
            $posts = Post::where('title', 'like', "%{$keyword}%")
                        ->orWhere('body', 'like', "%{$keyword}%")
                        ->paginate(10);
        } else {
            $posts = Post::paginate(10);
        }

        return view('search.post_search', compact('posts', 'keyword'));
    }

    public function myPost($id)
    {
        $post = Post::findOrFail($id);

        if (auth()->id() !== $post->user_id) {
            return redirect()->route('posts.show', $id);
        }

        return view('my_post', compact('post'));
    }

    public function myProduct($id)
    {
        $product = Product::with('user')->findOrFail($id);

        if (auth()->id() !== $product->user_id) {
            return redirect()->route('product.show', $id);
        }

        return view('my_product', compact('product'));
    }


        public function editAccount()
    {
        $user = Auth::user();
        return view('user.user_edit', compact('user'));
    }


    public function showProduct($id)
    {
        $product = Product::with('user')->findOrFail($id);
        return view('product_detail', compact('product'));
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }

}
