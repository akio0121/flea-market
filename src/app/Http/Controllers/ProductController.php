<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //商品詳細画面を表示する
    public function detail($id)
    {
        $product = Product::with([
            'productCategories.category',
            'comments.user'
        ])
            ->withCount(['goods', 'comments'])
            ->findOrFail($id);

        return view('product.detail', compact('product'));
    }

    //商品詳細画面を表示する
    public function exhibit(Product $product)
    {
        return view('product.exhibit');
    }
}
