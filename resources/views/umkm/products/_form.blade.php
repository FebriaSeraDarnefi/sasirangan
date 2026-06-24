@php
    $editing = isset($product);
@endphp

@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5">
        <p class="font-semibold text-red-700">
            Terdapat data yang belum sesuai:
        </p>

        <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid gap-8 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">
                Informasi Utama
            </h2>

            <div class="mt-6 space-y-5">
                <div>
                    <label
                        for="name"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Nama Produk
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $product->name ?? '') }}"
                        required
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label
                            for="price"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Harga
                        </label>

                        <input
                            id="price"
                            name="price"
                            type="number"
                            min="0"
                            step="1"
                            value="{{ old('price', isset($product) ? (float) $product->price : '') }}"
                            required
                            class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        >
                    </div>

                    <div>
                        <label
                            for="stock"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Stok
                        </label>

                        <input
                            id="stock"
                            name="stock"
                            type="number"
                            min="0"
                            value="{{ old('stock', $product->stock ?? 0) }}"
                            required
                            class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        >
                    </div>
                </div>

                <div>
                    <label
                        for="description"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Deskripsi Produk
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="6"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >{{ old('description', $product->description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">
                Detail Kain dan Motif
            </h2>

            <div class="mt-6 space-y-5">
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label
                            for="size"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Ukuran
                        </label>

                        <input
                            id="size"
                            name="size"
                            type="text"
                            value="{{ old('size', $product->size ?? '') }}"
                            placeholder="Contoh: 2 meter × 1,15 meter"
                            class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        >
                    </div>

                    <div>
                        <label
                            for="material"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Jenis Kain atau Bahan
                        </label>

                        <input
                            id="material"
                            name="material"
                            type="text"
                            value="{{ old('material', $product->material ?? '') }}"
                            placeholder="Contoh: Katun premium"
                            class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                        >
                    </div>
                </div>

                <div>
                    <label
                        for="motif_name"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Nama Motif
                    </label>

                    <input
                        id="motif_name"
                        name="motif_name"
                        type="text"
                        value="{{ old('motif_name', $product->motif_name ?? '') }}"
                        placeholder="Contoh: Gigi Haruan"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >
                </div>

                <div>
                    <label
                        for="motif_philosophy"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Filosofi Motif
                    </label>

                    <textarea
                        id="motif_philosophy"
                        name="motif_philosophy"
                        rows="4"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >{{ old('motif_philosophy', $product->motif_philosophy ?? '') }}</textarea>
                </div>

                <div>
                    <label
                        for="color_philosophy"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Filosofi Warna
                    </label>

                    <textarea
                        id="color_philosophy"
                        name="color_philosophy"
                        rows="4"
                        class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
                    >{{ old('color_philosophy', $product->color_philosophy ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">
                Foto Produk
            </h2>

            @if ($editing && $product->main_image)
                <img
                    src="{{ asset('storage/'.$product->main_image) }}"
                    alt="{{ $product->name }}"
                    class="mt-5 aspect-square w-full rounded-2xl object-cover"
                >
            @endif

            <div class="mt-5">
                <label
                    for="main_image"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Foto Utama
                </label>

                <input
                    id="main_image"
                    name="main_image"
                    type="file"
                    accept=".jpg,.jpeg,.png,.webp"
                    @required(! $editing)
                    class="block w-full rounded-xl border border-slate-300 bg-white p-2 text-sm"
                >

                <p class="mt-2 text-xs text-slate-500">
                    JPG, JPEG, PNG, atau WebP. Maksimal 3 MB.
                </p>
            </div>

            <div class="mt-5">
                <label
                    for="images"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Foto Tambahan
                </label>

                <input
                    id="images"
                    name="images[]"
                    type="file"
                    accept=".jpg,.jpeg,.png,.webp"
                    multiple
                    class="block w-full rounded-xl border border-slate-300 bg-white p-2 text-sm"
                >

                <p class="mt-2 text-xs text-slate-500">
                    Maksimal lima foto tambahan.
                </p>
            </div>

            @if ($editing && $product->images->isNotEmpty())
                <div class="mt-6 grid grid-cols-2 gap-3">
                    @foreach ($product->images as $image)
                        <label class="relative cursor-pointer">
                            <img
                                src="{{ asset('storage/'.$image->image_path) }}"
                                alt="Foto tambahan"
                                class="aspect-square w-full rounded-xl object-cover"
                            >

                            <span class="mt-2 flex items-center gap-2 text-xs text-red-600">
                                <input
                                    type="checkbox"
                                    name="delete_images[]"
                                    value="{{ $image->id }}"
                                    class="rounded border-slate-300 text-red-600 focus:ring-red-500"
                                >

                                Hapus foto
                            </span>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <label
                for="status"
                class="mb-2 block text-sm font-semibold text-slate-700"
            >
                Status Produk
            </label>

            <select
                id="status"
                name="status"
                class="w-full rounded-xl border-slate-300 focus:border-violet-500 focus:ring-violet-500"
            >
                <option
                    value="active"
                    @selected(
                        old(
                            'status',
                            $product->status ?? 'active'
                        ) === 'active'
                    )
                >
                    Aktif
                </option>

                <option
                    value="inactive"
                    @selected(
                        old(
                            'status',
                            $product->status ?? ''
                        ) === 'inactive'
                    )
                >
                    Nonaktif
                </option>
            </select>

            <p class="mt-2 text-xs leading-5 text-slate-500">
                Produk aktif akan ditampilkan pada katalog Customer.
            </p>
        </div>

        @if ($editing)
            <div class="rounded-2xl bg-slate-900 p-6 text-white">
                <p class="text-sm text-slate-400">
                    Kode UPC
                </p>

                <p class="mt-2 font-mono font-bold">
                    {{ $product->upc }}
                </p>

                <p class="mt-3 text-xs leading-5 text-slate-400">
                    Kode UPC tidak berubah ketika produk diedit.
                </p>
            </div>
        @endif
    </div>
</div>

<div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
    <a
        href="{{ route('umkm.products.index') }}"
        class="rounded-xl border border-slate-300 bg-white px-6 py-3 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50"
    >
        Batal
    </a>

    <button
        type="submit"
        class="rounded-xl bg-violet-700 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-200 hover:bg-violet-800"
    >
        {{ $editing ? 'Simpan Perubahan' : 'Tambah Produk' }}
    </button>
</div>