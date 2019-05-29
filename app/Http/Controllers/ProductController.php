<?php

namespace App\Http\Controllers;

use App\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        $cart = session()->get('cart');
        $totalProductCountOnCart = 0;
        if (!empty($cart) && count($cart) > 0) {
            foreach ($cart as $productId => $detail) {
                $totalProductCountOnCart += $detail['quantity'];
            }
        }

        return view('homepage', ['products' => $products, 'totalProductCount' => $totalProductCountOnCart]);
    }

    public function cart()
    {
        $cart = session()->get('cart');
        $summary['totalProductCount'] = 0;
        $summary['totalPrice'] = 0;
        if (!empty($cart) && count($cart) > 0) {
            foreach ($cart as $productId => $detail) {
                $summary['totalProductCount'] += $detail['quantity'];
                $summary['totalPrice'] += $detail['price'] * $detail['quantity'];
            }
        } else {
            $cart = null;
        }
        $summary['totalPrice'] = number_format((float)$summary['totalPrice'], 2, '.', '');
        return view('cart', ['cart' => $cart, 'summary' => $summary]);
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('productId');
        $product = Product::find($productId);

        // Ürün kontrolü
        if (empty($product)) {
            return response()->json([
                'message' => 'Ürün bulunamadı.',
            ], 404);
        }

        $cart = session()->get('cart');
 
        // Eğer sepet yoksa, sepet oluşturulacak ve ürün eklenecek.
        if (empty($cart)) {
            session()->put('cart', [
                $productId => [
                    "name"      => $product->name,
                    "quantity"  => 1,
                    "price"     => $product->price,
                    "image"     => $product->image
                ]
            ]);
            
            return response()->json([
                'message' => $product->name.' ürünü sepetinize eklendi.',
                'totalPrice' => number_format((float)$product->price, 2, '.', '')
            ], 200);
        }
 
        // Eğer sepet varsa ve içerisinde bu ürün de varsa, adet artırılacak.
        if(isset($cart[$productId])) {
            // Eğer stok sayısından fazla eklenmek istenirse hata verilecek.
            if ($cart[$productId]['quantity'] >= $product->stock) {
                return response()->json([
                    'message' => $product->name.' ürünü stoklarda '.$product->stock.' adet bulunmaktadır. Daha fazla ekleyemezsiniz.',
                ], 404);
            }

            $cart[$productId]['quantity']++;
            session()->put('cart', $cart);
            
            $totalPrice = 0;
            if (count($cart) > 0) {
                foreach ($cart as $productId => $detail) {
                    $totalPrice += $detail['price'] * $detail['quantity'];
                }
            }
            return response()->json([
                'message' => $product->name.' ürününün sepetteki adedi '.$cart[$productId]['quantity'].' olarak güncellendi.',
                'totalPrice' => number_format((float)$totalPrice, 2, '.', '')
            ], 200);
        }
 
        // Eğer sepet varsa ama içerisinde bu ürün yoksa eklenecek.
        $cart[$productId] = [
            "name"      => $product->name,
            "quantity"  => 1,
            "price"     => $product->price,
            "image"     => $product->image
        ];
 
        session()->put('cart', $cart);

        return response()->json([
            'message' => $product->name.' ürün sepetinize eklendi.',
        ], 200);
    }

    public function removeToCart(Request $request)
    {
        $productId = $request->input('productId');
        $product = Product::find($productId);

        // Ürün kontrolü
        if (empty($product)) {
            return response()->json([
                'message' => 'Ürün bulunamadı.',
            ], 404);
        }

        $cart = session()->get('cart');
 
        // Eğer sepet yoksa, hata dönecek.
        if (empty($cart)) {
            return response()->json([
                'message' => 'Sepet bulunamadı.',
            ], 404);
        }
 
        // Eğer sepet varsa ve içerisinde bu ürün de varsa, adet azaltılacak.
        if(isset($cart[$productId])) {
            // Eğer 1 adet olan ürün silinirse
            if ($cart[$productId]['quantity'] <= 1) {
                unset($cart[$productId]);
                session()->put('cart', $cart);
                $totalPrice = 0;
                if (count($cart) > 0) {
                    foreach ($cart as $productId => $detail) {
                        $totalPrice += $detail['price'] * $detail['quantity'];
                    }
                }
                return response()->json([
                    'message'       => $product->name.' ürünü sepetinizden kaldırıldı.',
                    'totalPrice'    => number_format((float)$totalPrice, 2, '.', '')
                ], 200);
            }

            $cart[$productId]['quantity']--;
            session()->put('cart', $cart);
            
            $totalPrice = 0;
            if (count($cart) > 0) {
                foreach ($cart as $productId => $detail) {
                    $totalPrice += $detail['price'] * $detail['quantity'];
                }
            }
            return response()->json([
                'message'       => $product->name.' ürününün sepetteki adedi '.$cart[$productId]['quantity'].' olarak güncellendi.',
                'totalPrice'    => number_format((float)$totalPrice, 2, '.', '')
            ], 200);
        }
 
        // Eğer sepet varsa ama içerisinde bu ürün yoksa hata dönecek.
        return response()->json([
            'message' => 'Sepetinizde bu ürün bulunamadı.',
        ], 404);
        
    }
}