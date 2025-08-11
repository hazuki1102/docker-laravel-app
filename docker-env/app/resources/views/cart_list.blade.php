@extends('layouts.layout')

@section('content')
<main class="py-4">
    <div class="container">
        <h3>カート一覧</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(empty($cart))
            <p class="text-muted text-center">カートは空です</p>
            <div class="text-center">
                <a href="{{ route('home') }}" class="btn btn-primary">商品を探す</a>
            </div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>商品画像</th>
                        <th>商品名</th>
                        <th>価格</th>
                        <th>数量</th>
                        <th>小計</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cart as $id => $item)
                        @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                        <tr>
                            <td><img src="{{ url($item['file_path']) }}" width="80"></td>
                            <td>{{ $item['title'] }}</td>
                            <td>{{ $item['price'] }}円</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>{{ $subtotal }}円</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-right">
                <h5>合計: {{ $total }}円</h5>
            </div>
            <div class="text-right mt-3">
                <button type="button" class="btn btn-success btn-lg">
                    購入に進む
                </button>
            </div>

        @endif
    </div>
</main>
@endsection
