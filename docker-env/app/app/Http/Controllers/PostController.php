<?php

namespace App\Http\Controllers;

use App\Models\Post;
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
        return view('home');
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
        $imageUrl = Storage::url($newPath);

        Post::create([
            'user_id' => Auth::id(),
            'title' => $request->input('title'),
            'body' => $request->input('body', ''),
            'hashtags' => $request->input('hashtags', ''),
            'materials' => $request->input('materials', ''),
            'image_path' => $imageUrl,
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ($id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $user = Auth::user();
        return view('user.user_edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        return view('mypage');
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
            $validated['temp_image_path'] = $tempPath;
        } else {
            return back()->withErrors(['image' => '画像のアップロードに失敗しました。'])->withInput();
        }

        return view('create.create_post_conf', ['data' => $validated]);
    }



    public function productConf()
    {
        return view('create.create_product_conf');
    }
        public function editConf()
    {
        return view('user.edit_conf');
    }
        public function deleteConf()
    {
        return view('user.delete_conf');
    }
}
