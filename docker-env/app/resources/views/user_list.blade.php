@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3 text-center">ユーザー検索</h2>

    <form method="GET" action="{{ route('user.list') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control" placeholder="ユーザー名で検索" value="{{ request('keyword') }}">
            <button class="btn btn-primary">検索</button>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ユーザーID</th>
                <th>ユーザー名</th>
                <th>投稿数</th>
                <th>ブックマーク数</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->posts_count }}</td>
                <td>{{ $user->bookmarks_count }}</td>
                <td>
                    <a href="{{ url('/user_page?user_id=' . $user->id) }}" class="btn btn-sm btn-outline-secondary">詳細</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->appends(request()->query())->links() }}
</div>
@endsection
