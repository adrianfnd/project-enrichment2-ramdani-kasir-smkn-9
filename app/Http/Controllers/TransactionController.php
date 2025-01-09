<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stocks = Stock::where('quantity', '>', 0)->get();
        
        $carts = Cart::where('user_id', Auth::id())
                    ->with('stock')
                    ->get();

        $total = $carts->sum(function($cart) {
            return $cart->quantity * $cart->stock->price;
        });

        return view('admin.transaction.pos', compact('stocks', 'carts', 'total'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $stock = Stock::findOrFail($request->stock_id);

        if($stock->quantity < $request->quantity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok tidak mencukupi'
            ], 422);
        }

        $cart = Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'stock_id' => $request->stock_id
            ],
            [
                'quantity' => \DB::raw('quantity + ' . $request->quantity)
            ]
        );

        $carts = Cart::where('user_id', Auth::id())
                    ->with('stock')
                    ->get();
        
        $total = $carts->sum(function($cart) {
            return $cart->quantity * $cart->stock->price;
        });

        $cartHtml = view('admin.transaction.cart-items', compact('carts', 'total'))->render();

        return response()->json([
            'status' => 'success',
            'message' => 'Produk ditambahkan ke keranjang',
            'cartHtml' => $cartHtml
        ]);
    }

    public function removeFromCart(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())
                    ->where('id', $request->cart_id)
                    ->firstOrFail();

        $cart->delete();

        $carts = Cart::where('user_id', Auth::id())
                    ->with('stock')
                    ->get();
        
        $total = $carts->sum(function($cart) {
            return $cart->quantity * $cart->stock->price;
        });

        $cartHtml = view('admin.transaction.cart-items', compact('carts', 'total'))->render();

        return response()->json([
            'status' => 'success',
            'message' => 'Produk dihapus dari keranjang',
            'cartHtml' => $cartHtml
        ]);
    }

    public function checkout(Request $request)
    {
        \DB::beginTransaction();
        
        try {
            $carts = Cart::where('user_id', Auth::id())
                        ->with('stock')
                        ->get();
    
            if($carts->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Keranjang kosong'
                ], 422);
            }
    
            foreach($carts as $cart) {
                if($cart->stock->quantity < $cart->quantity) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Stok {$cart->stock->name} tidak mencukupi"
                    ], 422);
                }
            }
    
            $transaction = new Transaction();
            $transaction->user_id = Auth::id();
            $transaction->total = $carts->sum(function($cart) {
                return $cart->quantity * $cart->stock->price;
            });
            $transaction->save();
    
            foreach($carts as $cart) {
                $stock = Stock::find($cart->stock_id);
                $stock->quantity = $stock->quantity - $cart->quantity;
                $stock->save();

                $transaction->details()->create([
                    'stock_id' => $cart->stock_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->stock->price
                ]);

                $cart->delete();
            }
    
            \DB::commit();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil',
                'transaction_id' => $transaction->id
            ]);
    
        } catch (\Exception $e) {
            \DB::rollback();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat checkout: ' . $e->getMessage()
            ], 500);
        }
    }
}