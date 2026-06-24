<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Umkm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class ProductController extends Controller
{
    public function index(): View
    {
        $umkm = $this->currentUmkm();

        $products = $umkm->products()
            ->withCount('images')
            ->latest()
            ->paginate(10);

        $statistics = [
            'total' => $umkm->products()->count(),

            'active' => $umkm->products()
                ->where('status', 'active')
                ->count(),

            'inactive' => $umkm->products()
                ->where('status', 'inactive')
                ->count(),

            'out_of_stock' => $umkm->products()
                ->where('stock', 0)
                ->count(),
        ];

        return view('umkm.products.index', compact(
            'umkm',
            'products',
            'statistics'
        ));
    }

    public function create(): View
    {
        $umkm = $this->currentUmkm();

        $this->ensureActive($umkm);

        return view('umkm.products.create');
    }

    public function store(
        StoreProductRequest $request
    ): RedirectResponse {
        $umkm = $this->currentUmkm();

        $mainImagePath = $request
            ->file('main_image')
            ->store('products/main', 'public');

        $galleryPaths = [];

        foreach ($request->file('images', []) as $image) {
            $galleryPaths[] = $image->store(
                'products/gallery',
                'public'
            );
        }

        try {
            $product = DB::transaction(function () use (
                $request,
                $umkm,
                $mainImagePath,
                $galleryPaths
            ) {
                $data = $request->safe()->except([
                    'main_image',
                    'images',
                ]);

                $data['umkm_id'] = $umkm->id;

                $data['slug'] = $this->generateUniqueSlug(
                    $data['name']
                );

                $data['upc'] = $this->generateUniqueUpc();

                $data['main_image'] = $mainImagePath;

                $data['view_count'] = 0;

                $product = Product::create($data);

                foreach ($galleryPaths as $path) {
                    $product->images()->create([
                        'image_path' => $path,
                    ]);
                }

                return $product;
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete([
                $mainImagePath,
                ...$galleryPaths,
            ]);

            throw $exception;
        }

        return redirect()
            ->route('umkm.products.index')
            ->with(
                'success',
                "Produk {$product->name} berhasil ditambahkan."
            );
    }

    public function edit(Product $product): View
    {
        Gate::authorize('update', $product);

        $umkm = $this->currentUmkm();

        $this->ensureActive($umkm);

        $product->load('images');

        return view(
            'umkm.products.edit',
            compact('product')
        );
    }

    public function update(
        UpdateProductRequest $request,
        Product $product
    ): RedirectResponse {
        Gate::authorize('update', $product);

        $newMainImagePath = null;
        $newGalleryPaths = [];

        if ($request->hasFile('main_image')) {
            $newMainImagePath = $request
                ->file('main_image')
                ->store('products/main', 'public');
        }

        foreach ($request->file('images', []) as $image) {
            $newGalleryPaths[] = $image->store(
                'products/gallery',
                'public'
            );
        }

        $deleteImageIds = collect(
            $request->input('delete_images', [])
        )
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        $imagesToDelete = $product->images()
            ->whereKey($deleteImageIds)
            ->pluck('image_path')
            ->all();

        $oldMainImagePath = $product->main_image;

        try {
            DB::transaction(function () use (
                $request,
                $product,
                $newMainImagePath,
                $newGalleryPaths,
                $deleteImageIds
            ) {
                $data = $request->safe()->except([
                    'main_image',
                    'images',
                    'delete_images',
                ]);

                $data['slug'] = $this->generateUniqueSlug(
                    $data['name'],
                    $product->id
                );

                if ($newMainImagePath !== null) {
                    $data['main_image'] = $newMainImagePath;
                }

                $product->update($data);

                if ($deleteImageIds->isNotEmpty()) {
                    $product->images()
                        ->whereKey($deleteImageIds)
                        ->delete();
                }

                foreach ($newGalleryPaths as $path) {
                    $product->images()->create([
                        'image_path' => $path,
                    ]);
                }
            });
        } catch (Throwable $exception) {
            Storage::disk('public')->delete([
                ...array_filter([
                    $newMainImagePath,
                ]),
                ...$newGalleryPaths,
            ]);

            throw $exception;
        }

        if (
            $newMainImagePath !== null
            && $oldMainImagePath !== null
        ) {
            Storage::disk('public')
                ->delete($oldMainImagePath);
        }

        if ($imagesToDelete !== []) {
            Storage::disk('public')
                ->delete($imagesToDelete);
        }

        return redirect()
            ->route('umkm.products.index')
            ->with(
                'success',
                "Produk {$product->name} berhasil diperbarui."
            );
    }

    public function destroy(
        Product $product
    ): RedirectResponse {
        Gate::authorize('delete', $product);

        $imagePaths = [
            $product->main_image,
            ...$product->images()
                ->pluck('image_path')
                ->all(),
        ];

        DB::transaction(function () use ($product) {
            $product->delete();
        });

        Storage::disk('public')->delete(
            array_filter($imagePaths)
        );

        return redirect()
            ->route('umkm.products.index')
            ->with(
                'success',
                'Produk berhasil dihapus.'
            );
    }

    private function currentUmkm(): Umkm
    {
        $user = auth()->user();

        abort_if(
            $user === null || $user->umkm === null,
            404,
            'Profil UMKM tidak ditemukan.'
        );

        return $user->umkm;
    }

    private function ensureActive(Umkm $umkm): void
    {
        abort_unless(
            $umkm->verification_status === 'active',
            403,
            'UMKM harus disetujui Admin sebelum mengelola produk.'
        );
    }

    private function generateUniqueSlug(
        string $name,
        ?int $ignoreProductId = null
    ): string {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'produk';
        }

        $slug = $baseSlug;
        $number = 2;

        while (
            Product::query()
                ->where('slug', $slug)
                ->when(
                    $ignoreProductId !== null,
                    function ($query) use ($ignoreProductId) {
                        $query->where(
                            'id',
                            '!=',
                            $ignoreProductId
                        );
                    }
                )
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$number}";
            $number++;
        }

        return $slug;
    }

    private function generateUniqueUpc(): string
    {
        do {
            $number = str_pad(
                (string) random_int(1, 999999),
                6,
                '0',
                STR_PAD_LEFT
            );

            $upc = 'SVR-'
                .now()->format('Y')
                .'-'
                .$number;
        } while (
            Product::query()
                ->where('upc', $upc)
                ->exists()
        );

        return $upc;
    }
}
