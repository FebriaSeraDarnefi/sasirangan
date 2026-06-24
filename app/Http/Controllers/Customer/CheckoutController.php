<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout.
     */
    public function create(): View|RedirectResponse
    {
        $cart = Cart::query()
            ->where('user_id', auth()->id())
            ->with([
                'items.product.umkm',
            ])
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()
                ->route('customer.cart.index')
                ->with(
                    'error',
                    'Keranjang masih kosong.'
                );
        }

        $unavailableItem = $cart->items->first(function ($item) {
            $product = $item->product;

            return ! $product
                || ! $product->umkm
                || $product->status !== 'active'
                || $product->umkm->verification_status !== 'active'
                || $product->stock < $item->quantity;
        });

        if ($unavailableItem) {
            return redirect()
                ->route('customer.cart.index')
                ->with(
                    'error',
                    'Terdapat produk yang tidak tersedia atau stoknya tidak mencukupi.'
                );
        }

        $groupedItems = $cart->items->groupBy(
            fn ($item) => $item->product->umkm_id
        );

        $subtotal = $cart->items->sum(
            fn ($item) => (
                (float) $item->price
                * $item->quantity
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Ongkos Kirim
        |--------------------------------------------------------------------------
        |
        | Untuk sementara ongkos kirim dibuat Rp0.
        | Nantinya dapat dihitung berdasarkan alamat atau setiap UMKM.
        |
        */

        $shippingCost = 0;

        $totalAmount = $subtotal + $shippingCost;

        $user = auth()->user();

        return view('store.checkout.create', compact(
            'cart',
            'groupedItems',
            'subtotal',
            'shippingCost',
            'totalAmount',
            'user'
        ));
    }

    /**
     * Memproses checkout dan membuat pesanan.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'recipient_name' => [
                'required',
                'string',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'max:30',
            ],
            'address' => [
                'required',
                'string',
                'max:2000',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ], [
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'address.required' => 'Alamat pengiriman wajib diisi.',
        ]);

        $order = DB::transaction(function () use ($validated): Order {
            $cart = Cart::query()
                ->where('user_id', auth()->id())
                ->with([
                    'items',
                ])
                ->lockForUpdate()
                ->first();

            if (! $cart || $cart->items->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Keranjang masih kosong.',
                ]);
            }

            $checkoutItems = [];
            $subtotal = 0;

            foreach ($cart->items as $cartItem) {
                $product = Product::query()
                    ->with('umkm')
                    ->lockForUpdate()
                    ->find($cartItem->product_id);

                if (! $product) {
                    throw ValidationException::withMessages([
                        'cart' => 'Salah satu produk sudah tidak tersedia.',
                    ]);
                }

                if (
                    $product->status !== 'active'
                    || ! $product->umkm
                    || $product->umkm->verification_status !== 'active'
                ) {
                    throw ValidationException::withMessages([
                        'cart' => 'Produk '.$product->name
                            .' sedang tidak tersedia.',
                    ]);
                }

                if ($product->stock < $cartItem->quantity) {
                    throw ValidationException::withMessages([
                        'cart' => 'Stok produk '.$product->name
                            .' tidak mencukupi. Stok tersedia: '
                            .$product->stock.'.',
                    ]);
                }

                $price = (float) $cartItem->price;

                $itemSubtotal = $price
                    * $cartItem->quantity;

                $subtotal += $itemSubtotal;

                $checkoutItems[] = [
                    'product' => $product,
                    'price' => $price,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $itemSubtotal,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Total Pesanan
            |--------------------------------------------------------------------------
            */

            $shippingCost = 0;

            $totalAmount = $subtotal + $shippingCost;

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => $this->generateOrderNumber(),
                'recipient_name' => $validated['recipient_name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
            ]);

            foreach ($checkoutItems as $checkoutItem) {
                $product = $checkoutItem['product'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'umkm_id' => $product->umkm_id,
                    'product_name' => $product->name,
                    'price' => $checkoutItem['price'],
                    'quantity' => $checkoutItem['quantity'],
                    'subtotal' => $checkoutItem['subtotal'],
                ]);

                $product->decrement(
                    'stock',
                    $checkoutItem['quantity']
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Kosongkan Keranjang
            |--------------------------------------------------------------------------
            */

            $cart->items()->delete();

            return $order;
        });

        return redirect()
            ->route(
                'customer.checkout.success',
                $order
            )
            ->with(
                'success',
                'Pesanan berhasil dibuat.'
            );
    }

    /**
     * Menampilkan halaman checkout berhasil.
     */
    public function success(
        Order $order
    ): View {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        $order->load([
            'items.umkm',
            'items.product',
            'payment',
        ]);

        return view(
            'store.checkout.success',
            compact('order')
        );
    }

    /**
     * Membuat nomor pesanan yang unik.
     */
    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'SVR-'
                .now()->format('Ymd')
                .'-'
                .random_int(100000, 999999);
        } while (
            Order::query()
                ->where('order_number', $orderNumber)
                ->exists()
        );

        return $orderNumber;
    }
}
