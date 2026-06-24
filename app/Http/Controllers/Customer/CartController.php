<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Menampilkan isi keranjang customer.
     */
    public function index(): View
    {
        $cart = Cart::firstOrCreate([
            'user_id' => auth()->id(),
        ]);

        $cart->load([
            'items.product.umkm',
        ]);

        $groupedItems = $cart->items->groupBy(
            fn (CartItem $item) => $item->product->umkm_id
        );

        $total = $cart->items->sum(
            fn (CartItem $item) => (
                (float) $item->price
                * $item->quantity
            )
        );

        $totalQuantity = $cart->items->sum('quantity');

        return view('store.cart.index', compact(
            'cart',
            'groupedItems',
            'total',
            'totalQuantity'
        ));
    }

    /**
     * Menambahkan produk ke keranjang.
     */
    public function store(
        Request $request,
        Product $product
    ): RedirectResponse {
        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $product->loadMissing('umkm');

        if (
            $product->status !== 'active'
            || ! $product->umkm
            || $product->umkm->verification_status !== 'active'
        ) {
            return back()->with(
                'error',
                'Produk saat ini tidak tersedia.'
            );
        }

        if ($product->stock <= 0) {
            return back()->with(
                'error',
                'Stok produk sedang habis.'
            );
        }

        DB::transaction(function () use (
            $product,
            $validated
        ): void {
            $cart = Cart::firstOrCreate([
                'user_id' => auth()->id(),
            ]);

            $cartItem = CartItem::query()
                ->where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first();

            $currentQuantity = $cartItem?->quantity ?? 0;

            $newQuantity = $currentQuantity
                + $validated['quantity'];

            if ($newQuantity > $product->stock) {
                throw ValidationException::withMessages([
                    'quantity' => 'Jumlah produk melebihi stok. Stok tersedia: '
                        .$product->stock.'.',
                ]);
            }

            if ($cartItem) {
                $cartItem->update([
                    'quantity' => $newQuantity,
                    'price' => $product->price,
                ]);

                return;
            }

            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price' => $product->price,
            ]);
        });

        return redirect()
            ->route('customer.cart.index')
            ->with(
                'success',
                'Produk berhasil ditambahkan ke keranjang.'
            );
    }

    /**
     * Memperbarui jumlah barang dalam keranjang.
     */
    public function update(
        Request $request,
        CartItem $cartItem
    ): RedirectResponse {
        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $cartItem->loadMissing([
            'cart',
            'product.umkm',
        ]);

        $this->ensureOwnedByCustomer($cartItem);

        $product = $cartItem->product;

        if (
            $product->status !== 'active'
            || ! $product->umkm
            || $product->umkm->verification_status !== 'active'
        ) {
            return back()->with(
                'error',
                'Produk saat ini tidak tersedia.'
            );
        }

        if ($product->stock <= 0) {
            return back()->with(
                'error',
                'Stok produk sedang habis.'
            );
        }

        if ($validated['quantity'] > $product->stock) {
            return back()->with(
                'error',
                'Jumlah produk melebihi stok. Stok tersedia: '
                    .$product->stock.'.'
            );
        }

        $cartItem->update([
            'quantity' => $validated['quantity'],
        ]);

        return back()->with(
            'success',
            'Jumlah produk berhasil diperbarui.'
        );
    }

    /**
     * Menghapus satu barang dari keranjang.
     */
    public function destroy(
        CartItem $cartItem
    ): RedirectResponse {
        $cartItem->loadMissing('cart');

        $this->ensureOwnedByCustomer($cartItem);

        $cartItem->delete();

        return back()->with(
            'success',
            'Produk berhasil dihapus dari keranjang.'
        );
    }

    /**
     * Mengosongkan semua isi keranjang.
     */
    public function clear(): RedirectResponse
    {
        $cart = Cart::query()
            ->where('user_id', auth()->id())
            ->first();

        if ($cart) {
            $cart->items()->delete();
        }

        return back()->with(
            'success',
            'Keranjang berhasil dikosongkan.'
        );
    }

    /**
     * Memastikan item keranjang dimiliki customer yang sedang login.
     */
    private function ensureOwnedByCustomer(
        CartItem $cartItem
    ): void {
        abort_unless(
            $cartItem->cart
            && $cartItem->cart->user_id === auth()->id(),
            403
        );
    }
}
