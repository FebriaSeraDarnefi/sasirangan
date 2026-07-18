<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductView;
use App\Models\Umkm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function home(): View
    {
        $popularProducts = $this->activeProducts()
            ->orderByDesc('view_count')
            ->take(8)
            ->get();

        $latestProducts = $this->activeProducts()
            ->latest()
            ->take(8)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Semua UMKM aktif
        |--------------------------------------------------------------------------
        |
        | Tidak lagi menggunakan take(4), sehingga seluruh UMKM aktif akan
        | ditampilkan pada halaman utama.
        |
        */
        $umkms = Umkm::query()
            ->withCount([
                'products' => fn (Builder $query) => $query
                    ->where('status', 'active'),
            ])
            ->where('verification_status', 'active')
            ->orderBy('business_name')
            ->get();

        $motifs = $this->getMotifs();

        return view('store.home', compact(
            'popularProducts',
            'latestProducts',
            'umkms',
            'motifs'
        ));
    }

    public function education(): View
    {
        return view('store.education');
    }

    public function catalog(Request $request): View
    {
        $productsQuery = $this->activeProducts();

        $productsQuery
            ->when(
                $request->filled('search'),
                function (Builder $query) use ($request): void {
                    $search = trim(
                        (string) $request->input('search')
                    );

                    $query->where(
                        function (Builder $query) use ($search): void {
                            $query
                                ->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'motif_name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'material',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'description',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhereHas(
                                    'umkm',
                                    function (
                                        Builder $query
                                    ) use ($search): void {
                                        $query->where(
                                            'business_name',
                                            'like',
                                            "%{$search}%"
                                        );
                                    }
                                );
                        }
                    );
                }
            )
            ->when(
                $request->filled('motif'),
                function (Builder $query) use ($request): void {
                    $query->where(
                        'motif_name',
                        $request->input('motif')
                    );
                }
            )
            ->when(
                $request->filled('material'),
                function (Builder $query) use ($request): void {
                    $query->where(
                        'material',
                        $request->input('material')
                    );
                }
            )
            ->when(
                $request->filled('min_price'),
                function (Builder $query) use ($request): void {
                    $query->where(
                        'price',
                        '>=',
                        $request->integer('min_price')
                    );
                }
            )
            ->when(
                $request->filled('max_price'),
                function (Builder $query) use ($request): void {
                    $query->where(
                        'price',
                        '<=',
                        $request->integer('max_price')
                    );
                }
            );

        match ($request->input('sort')) {
            'oldest' => $productsQuery->oldest(),

            'price_low' => $productsQuery
                ->orderBy('price'),

            'price_high' => $productsQuery
                ->orderByDesc('price'),

            'popular' => $productsQuery
                ->orderByDesc('view_count'),

            default => $productsQuery->latest(),
        };

        $products = $productsQuery
            ->paginate(12)
            ->withQueryString();

        $motifs = $this->getMotifs();

        $materials = $this->activeProducts()
            ->whereNotNull('material')
            ->where('material', '!=', '')
            ->select('material')
            ->distinct()
            ->orderBy('material')
            ->pluck('material');

        return view('store.catalog', compact(
            'products',
            'motifs',
            'materials'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Profil dan katalog UMKM
    |--------------------------------------------------------------------------
    */
    public function umkmProfile(
        Request $request,
        Umkm $umkm
    ): View {
        /*
        | Profil UMKM yang belum aktif tidak dapat dibuka oleh publik.
        */
        abort_unless(
            $umkm->verification_status === 'active',
            404
        );

        $productsQuery = Product::query()
            ->with('umkm')
            ->where('umkm_id', $umkm->id)
            ->where('status', 'active')
            ->when(
                $request->filled('search'),
                function (Builder $query) use ($request): void {
                    $search = trim(
                        (string) $request->input('search')
                    );

                    $query->where(
                        function (Builder $query) use ($search): void {
                            $query
                                ->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'motif_name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'material',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'description',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            );

        match ($request->input('sort')) {
            'oldest' => $productsQuery->oldest(),

            'price_low' => $productsQuery
                ->orderBy('price'),

            'price_high' => $productsQuery
                ->orderByDesc('price'),

            'popular' => $productsQuery
                ->orderByDesc('view_count'),

            default => $productsQuery->latest(),
        };

        $products = $productsQuery
            ->paginate(12)
            ->withQueryString();

        return view('store.umkm-profile', compact(
            'umkm',
            'products'
        ));
    }

    public function show(Product $product): View
    {
        $product->load([
            'umkm.user',
            'images',
        ]);

        abort_unless(
            $product->status === 'active'
            && $product->umkm
            && $product->umkm->verification_status === 'active',
            404
        );

        $sessionKey = "viewed_products.{$product->id}";

        if (! session()->has($sessionKey)) {
            DB::transaction(function () use ($product): void {
                ProductView::create([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'session_identifier' => session()->getId(),
                    'viewed_at' => now(),
                ]);

                $product->increment('view_count');
            });

            session()->put($sessionKey, true);
        }

        $relatedProducts = $this->activeProducts()
            ->where('products.id', '!=', $product->id)
            ->where(
                function (Builder $query) use ($product): void {
                    $query
                        ->where(
                            'motif_name',
                            $product->motif_name
                        )
                        ->orWhere(
                            'umkm_id',
                            $product->umkm_id
                        );
                }
            )
            ->take(4)
            ->get();

        return view('store.show', compact(
            'product',
            'relatedProducts'
        ));
    }

    private function activeProducts(): Builder
    {
        return Product::query()
            ->with('umkm')
            ->where('status', 'active')
            ->whereHas(
                'umkm',
                function (Builder $query): void {
                    $query->where(
                        'verification_status',
                        'active'
                    );
                }
            );
    }

    private function getMotifs()
    {
        return $this->activeProducts()
            ->whereNotNull('motif_name')
            ->where('motif_name', '!=', '')
            ->select('motif_name')
            ->distinct()
            ->orderBy('motif_name')
            ->pluck('motif_name');
    }
}