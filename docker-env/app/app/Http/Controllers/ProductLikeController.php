<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductLike;

class ProductLikeController extends Controller
{
    public function toggle(Request $request, Product $product)
    {
        $user = $request->user();

        return DB::transaction(function () use ($user, $product) {
            $existing = ProductLike::where('user_id', $user->id)
                        ->where('product_id', $product->id)
                        ->first();

            if ($existing) {
                $existing->delete();
                $liked = false;
            } else {
                ProductLike::create([
                    'user_id'    => $user->id,
                    'product_id' => $product->id,
                ]);
                $liked = true;
            }

            return response()->json([
                'liked'        => $liked,
                'likes_count'  => $product->likes()->count(),
                'product_id'   => $product->id,
            ]);
        });
    }
}
