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
    // ホーム画面表示
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

    public function create()
    {
        return view('posts.create');
    }

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

        // XSS対策
        $title     = strip_tags($request->input('title'));
        $body      = strip_tags($request->input('body', ''));
        $hashtags  = strip_tags($request->input('hashtags', ''));
        $materials = strip_tags($request->input('materials', ''));

        Post::create([
            'user_id'    => Auth::id(),
            'title'      => $title,
            'body'       => $body,
            'hashtags'   => $hashtags,
            'materials'  => $materials,
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

        // XSS対策
        $validated['title']     = strip_tags($validated['title']);
        $validated['body']      = strip_tags($validated['body'] ?? '');
        $validated['hashtags']  = strip_tags($validated['hashtags'] ?? '');
        $validated['materials'] = strip_tags($validated['materials'] ?? '');

        return view('posts.confirm', ['data' => $validated]);
    }

    public function show($id)
    {
        $post = Post::with(['comments.user'])->findOrFail($id);
        return view('post_detail', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);

        if (auth()->id() !== $post->user_id) {
            return redirect()->route('posts.show', $id);
        }

        return view('edit.post_edit', compact('post'));
    }

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

        // XSS対策
        $validated['username'] = strip_tags($validated['username'] ?? $user->username);
        $validated['profile']  = strip_tags($validated['profile'] ?? '');

        if (!empty($validated['icon_path'])) {
            $from = $validated['icon_path'];
            $to   = 'icons/' . basename($from);
            Storage::disk('public')->move($from, $to);
            $user->icon_path = $to;
        }

        $user->username = $validated['username'] ?? $user->username;
        $user->email    = $validated['email'] ?? $user->email;
        $user->profile  = $validated['profile'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()->route('mypage')->with('success', 'プロフィールを更新しました。');
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

        // XSS対策
        $validated['title']     = strip_tags($validated['title']);
        $validated['body']      = strip_tags($validated['body'] ?? '');
        $validated['hashtags']  = strip_tags($validated['hashtags'] ?? '');
        $validated['materials'] = strip_tags($validated['materials'] ?? '');

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

        // XSS対策
        $validated['title']   = strip_tags($validated['title']);
        $validated['caption'] = strip_tags($validated['caption'] ?? '');
        $validated['tags']    = strip_tags($validated['tags'] ?? '');

        $productData = [
            'file_path' => $tempPath,
            'title' => $validated['title'],
            'caption' => $validated['caption'],
            'tags' => $validated['tags'],
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

        // XSS対策
        $validated['username'] = strip_tags($validated['username'] ?? '');
        $validated['profile']  = strip_tags($validated['profile'] ?? '');

        $validated['current_icon_path'] = optional(\Auth::user())->icon_path;

        return view('user.edit_conf', ['data' => $validated]);
    }

    public function deleteConf()
    {
        $user = Auth::user();
        return view('user.delete_conf', compact('user'));
    }


    public function search(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'nullable|string|max:100',
            'username'  => 'nullable|string|max:100',
            'body'      => 'nullable|string|max:500',
            'type'      => 'nullable|in:all,post,product',
            'date_from' => 'nullable|date',
            'date_to'   => 'nullable|date',
            'keyword'   => 'nullable|string|max:100',
        ]);

        $title    = trim($validated['title']    ?? '');
        $username = trim($validated['username'] ?? '');
        $body     = trim($validated['body']     ?? '');
        $type     = $validated['type']          ?? 'all';
        $dateFrom = $validated['date_from']     ?? null;
        $dateTo   = $validated['date_to']       ?? null;
        $keyword  = trim($validated['keyword']  ?? '');

        $esc = function ($v) { return addcslashes($v, '%_'); };

        $postQ = \App\Models\Post::with('user')->select('id','user_id','title','image_path','created_at')
            ->when($title   !== '', fn($q)=>$q->where('title','like','%'.$esc($title).'%'))
            ->when($body    !== '', fn($q)=>$q->where('body','like','%'.$esc($body).'%'))
            ->when($username!== '', fn($q)=>$q->whereHas('user', fn($uq)=>$uq->where('username','like','%'.$esc($username).'%')))
            ->when($dateFrom,       fn($q)=>$q->whereDate('created_at','>=',$dateFrom))
            ->when($dateTo,         fn($q)=>$q->whereDate('created_at','<=',$dateTo));

        $productQ = \App\Models\Product::with('user')->select('id','user_id','title', DB::raw('file_path as image_path'), 'created_at')
            ->when($title   !== '', fn($q)=>$q->where('title','like','%'.$esc($title).'%'))
            ->when($username!== '', fn($q)=>$q->whereHas('user', fn($uq)=>$uq->where('username','like','%'.$esc($username).'%')))
            ->when($dateFrom,       fn($q)=>$q->whereDate('created_at','>=',$dateFrom))
            ->when($dateTo,         fn($q)=>$q->whereDate('created_at','<=',$dateTo));

        if ($type === 'post') {
            $col = $postQ->get()->each(fn($i)=>$i->type='post');
        } elseif ($type === 'product') {
            $col = $productQ->get()->each(fn($i)=>$i->type='product');
        } else {
            $col = $postQ->get()->each(fn($i)=>$i->type='post')
                ->merge($productQ->get()->each(fn($i)=>$i->type='product'));
        }

        $sorted = $col->sortByDesc('created_at')->values();
        $page   = (int) $request->input('page', 1);
        $per    = 10;
        $paged  = $sorted->slice(($page-1)*$per, $per)->values();
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $paged, $sorted->count(), $per, $page,
            ['path'=>$request->url(),'query'=>$request->query()]
        );

        $kwPaginator = null;
        if ($keyword !== '') {
            $pQ = \App\Models\Post::with('user')->select('id','user_id','title','image_path','created_at')
                ->where(function($q) use($keyword,$esc){
                    $q->where('title','like','%'.$esc($keyword).'%')
                    ->orWhere('body','like','%'.$esc($keyword).'%')
                    ->orWhereHas('user', fn($uq)=>$uq->where('username','like','%'.$esc($keyword).'%'));
                })->get()->each(fn($i)=>$i->type='post');

            $prdQ = \App\Models\Product::with('user')->select('id','user_id','title', DB::raw('file_path as image_path'), 'created_at')
                ->where(function($q) use($keyword,$esc){
                    $q->where('title','like','%'.$esc($keyword).'%')
                    ->orWhereHas('user', fn($uq)=>$uq->where('username','like','%'.$esc($keyword).'%'));
                })->get()->each(fn($i)=>$i->type='product');

            $kw = $pQ->merge($prdQ)->sortByDesc('created_at')->values();

            $kpage = (int) $request->input('kpage', 1);
            $kper  = 12;
            $kpaged = $kw->slice(($kpage-1)*$kper, $kper)->values();
            $kwPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                $kpaged, $kw->count(), $kper, $kpage,
                ['path'=>$request->url(),'pageName'=>'kpage','query'=>$request->query()]
            );
        }

        return view('search.post_search', [
            'posts'   => $paginator,
            'filters' => compact('title','username','body','type','dateFrom','dateTo'),
            'keyword' => $keyword,
            'kwPosts' => $kwPaginator,
        ]);
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

    //アカウント削除
    public function destroyAccount(Request $request)
    {
        $user = $request->user();

        DB::beginTransaction();
        try {
            $user->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Account delete failed', ['user_id' => $user->id, 'e' => $e]);
            return back()->withErrors(['delete' => '削除に失敗しました。時間をおいて再度お試しください。']);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'アカウントを削除しました。');
    }

// 素材編集フォーム
    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        if (auth()->id() !== $product->user_id) {
            return redirect()->route('product.show', $id);
        }
        return view('edit.product_edit', compact('product'));
    }

    public function productEditConf(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if (auth()->id() !== $product->user_id) {
            return redirect()->route('product.show', $id);
        }

        $validated = $request->validate([
            'title'   => 'required|string|max:30',
            'caption' => 'nullable|string',
            'tags'    => 'nullable|string|max:255',
            'price'   => 'required|integer|min:0',
            'file'    => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $validated['tmp_file_path'] = $request->file('file')->store('tmp');
        }

        return view('edit.product_edit_conf', compact('product', 'validated'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if (auth()->id() !== $product->user_id) {
            return redirect()->route('product.show', $id);
        }

        $validated = $request->validate([
            'title'   => 'required|string|max:30',
            'caption' => 'nullable|string',
            'tags'    => 'nullable|string|max:255',
            'price'   => 'required|integer|min:0',
            'file'    => 'nullable|file|max:10240',
        ]);

        // XSS対策
        $validated['title']   = strip_tags($validated['title']);
        $validated['caption'] = strip_tags($validated['caption'] ?? '');
        $validated['tags']    = strip_tags($validated['tags'] ?? '');

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $tmp = $request->file('file')->store('tmp');
            $filename = basename($tmp);
            $final = 'public/products/'.$filename;
            Storage::move($tmp, $final);
            $product->file_path = 'products/'.$filename;
        }

        $product->title   = $validated['title'];
        $product->caption = $validated['caption'] ?? null;
        $product->tags    = $validated['tags'] ?? null;
        $product->price   = $validated['price'];
        $product->save();

        return redirect()->route('home', $product->id)->with('success', '素材を更新しました。');
    }

    public function deleteProductConf($id)
    {
        $product = Product::findOrFail($id);
        if (auth()->id() !== $product->user_id) {
            return redirect()->route('product.show', $id);
        }
        return view('edit.product_delete_conf', compact('product'));
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        if (auth()->id() !== $product->user_id) {
            return redirect()->route('product.show', $id);
        }

        $product->delete();

        return redirect()->route('mypage')->with('success', '素材を削除しました。');
    }

    // 投稿の編集確認
    public function postEditConf(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        if (auth()->id() !== $post->user_id) {
            return redirect()->route('posts.show', $id);
        }

        $validated = $request->validate([
            'image'     => 'nullable|image|max:10240',
            'title'     => 'required|string|max:30',
            'body'      => 'nullable|string',
            'hashtags'  => 'nullable|string',
            'materials' => 'nullable|string',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $tempPath  = $request->file('image')->store('tmp');
            $imageData = base64_encode(Storage::get($tempPath));
            $mimeType  = Storage::mimeType($tempPath);

            $validated['temp_image_path'] = $tempPath;
            $validated['image_base64']    = "data:$mimeType;base64,$imageData";
        }

        // XSS対策
        $validated['title']     = strip_tags($validated['title']);
        $validated['body']      = strip_tags($validated['body'] ?? '');
        $validated['hashtags']  = strip_tags($validated['hashtags'] ?? '');
        $validated['materials'] = strip_tags($validated['materials'] ?? '');

        return view('edit.post_edit_conf', ['post' => $post, 'data' => $validated]);
    }

    // 投稿の更新
    public function updatePost(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        if (auth()->id() !== $post->user_id) {
            return redirect()->route('posts.show', $id);
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:30',
            'body'         => 'nullable|string',
            'hashtags'     => 'nullable|string',
            'materials'    => 'nullable|string',
            'temp_image_path' => 'nullable|string',
        ]);

        if (!empty($validated['temp_image_path']) && Storage::exists($validated['temp_image_path'])) {
            $filename = basename($validated['temp_image_path']);
            $final    = 'public/images/'.$filename;
            Storage::move($validated['temp_image_path'], $final);
            $post->image_path = str_replace('public/', '', $final);
        }

        $post->title     = strip_tags($validated['title']);
        $post->body      = strip_tags($validated['body'] ?? '');
        $post->hashtags  = strip_tags($validated['hashtags'] ?? '');
        $post->materials = strip_tags($validated['materials'] ?? '');
        $post->save();

        return redirect()->route('home', $post->id)->with('success', '投稿を更新しました。');
    }

    public function deletePostConf($id)
    {
        $post = Post::findOrFail($id);
        return view('edit.post_delete_conf', compact('post'));
    }

}