<article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
    <a
        href="{{ route('store.product.show', $product) }}"
        class="block"
    >
        <div class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-violet-100 via-fuchsia-50 to-amber-50">
            @if ($product->main_image)
                <img
                    src="{{ asset('storage/'.$product->main_image) }}"
                    alt="{{ $product->name }}"
                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                >
            @else
                <div class="flex h-full flex-col items-center justify-center px-6 text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/80 text-violet-700 shadow-sm">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-9 w-9"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 19.5h16.5A1.5 1.5 0 0 0 21.75 18V6A1.5 1.5 0 0 0 20.25 4.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Z"
                            />
                        </svg>
                    </div>

                    <p class="mt-3 text-sm font-semibold text-violet-800">
                        Produk Sasirangan
                    </p>
                </div>
            @endif

            @if ($product->stock <= 0)
                <span class="absolute left-4 top-4 rounded-full bg-red-600 px-3 py-1 text-xs font-semibold text-white">
                    Stok habis
                </span>
            @elseif ($product->stock <= 5)
                <span class="absolute left-4 top-4 rounded-full bg-amber-500 px-3 py-1 text-xs font-semibold text-white">
                    Stok terbatas
                </span>
            @endif
        </div>
    </a>

    <div class="p-5">
        <p class="text-xs font-semibold uppercase tracking-wider text-violet-600">
            {{ $product->motif_name ?: 'Sasirangan' }}
        </p>

        <a href="{{ route('store.product.show', $product) }}">
            <h3 class="mt-2 min-h-12 text-base font-bold leading-6 text-slate-900 transition group-hover:text-violet-700">
                {{ \Illuminate\Support\Str::limit($product->name, 55) }}
            </h3>
        </a>

        <p class="mt-2 text-sm text-slate-500">
            {{ $product->umkm->business_name }}
        </p>

        <div class="mt-5 flex items-end justify-between gap-3">
            <div>
                <p class="text-xs text-slate-400">
                    Harga
                </p>

                <p class="text-lg font-bold text-violet-700">
                    Rp{{ number_format((float) $product->price, 0, ',', '.') }}
                </p>
            </div>

            <a
                href="{{ route('store.product.show', $product) }}"
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-700 transition hover:bg-violet-700 hover:text-white"
                aria-label="Lihat produk"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"
                    />
                </svg>
            </a>
        </div>
    </div>
</article>