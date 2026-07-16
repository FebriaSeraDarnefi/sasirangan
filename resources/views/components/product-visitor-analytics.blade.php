@props([
    'analytics',
    'showUmkm' => false,
])

@php
    $trend = collect($analytics['trend'] ?? []);
    $topProducts = collect($analytics['top_products'] ?? []);
    $mostVisitedProduct = $analytics['most_visited_product'] ?? null;
    $period = $analytics['period'] ?? 'month';
    $periodOptions = $analytics['period_options'] ?? [];
    $periodLabel = $analytics['period_label'] ?? '30 Hari Terakhir';
    $periodSummary = $analytics['period_summary'] ?? '';
    $customStart = old('visitor_start', $analytics['custom_start'] ?? '');
    $customEnd = old('visitor_end', $analytics['custom_end'] ?? '');
    $maximumDate = now()->toDateString();
    $maxTrendVisits = max(1, (int) $trend->max('visits'));
    $topProductVisits = max(
        1,
        (int) ($topProducts->first()?->period_views_count ?? 0)
    );
    $chartMinimumWidth = max(560, $trend->count() * 48);
@endphp

<section class="mt-8">
    <div class="mb-5 flex flex-col gap-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col justify-between gap-4 xl:flex-row xl:items-end">
            <div>
                <h2 class="text-xl font-bold text-slate-900">
                    Analisis Pengunjung Produk
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Statistik, grafik, dan produk teratas mengikuti periode yang dipilih.
                </p>
            </div>

            <div class="flex flex-wrap gap-2 rounded-2xl bg-slate-50 p-2">
                @foreach ($periodOptions as $value => $label)
                    @php
                        $presetQuery = array_merge(
                            request()->except([
                                'visitor_period',
                                'visitor_start',
                                'visitor_end',
                            ]),
                            ['visitor_period' => $value]
                        );
                        $presetUrl = url()->current()
                            .'?'.http_build_query($presetQuery);
                    @endphp

                    <a
                        href="{{ $presetUrl }}"
                        class="rounded-xl px-4 py-2 text-sm font-semibold transition {{ $period === $value
                            ? 'bg-violet-700 text-white shadow-sm'
                            : 'text-slate-600 hover:bg-violet-100 hover:text-violet-700' }}"
                        @if ($period === $value) aria-current="page" @endif
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="border-t border-slate-200 pt-5">
            <form
                method="GET"
                action="{{ url()->current() }}"
                class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto] lg:items-end"
            >
                @foreach (request()->except([
                    'visitor_period',
                    'visitor_start',
                    'visitor_end',
                ]) as $queryName => $queryValue)
                    @if (is_scalar($queryValue))
                        <input
                            type="hidden"
                            name="{{ $queryName }}"
                            value="{{ $queryValue }}"
                        >
                    @endif
                @endforeach

                <input type="hidden" name="visitor_period" value="custom">

                <div>
                    <label
                        for="visitor_start"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Tanggal mulai
                    </label>

                    <input
                        id="visitor_start"
                        type="date"
                        name="visitor_start"
                        value="{{ $customStart }}"
                        max="{{ $maximumDate }}"
                        required
                        onchange="const end = document.getElementById('visitor_end'); end.min = this.value; if (end.value && end.value < this.value) { end.value = this.value; }"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100"
                    >

                    @error('visitor_start')
                        <p class="mt-1.5 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="visitor_end"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Tanggal akhir
                        <span class="font-normal text-slate-400">
                            (opsional)
                        </span>
                    </label>

                    <input
                        id="visitor_end"
                        type="date"
                        name="visitor_end"
                        value="{{ $customEnd }}"
                        min="{{ $customStart }}"
                        max="{{ $maximumDate }}"
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-violet-500 focus:ring-4 focus:ring-violet-100"
                    >

                    @error('visitor_end')
                        <p class="mt-1.5 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    <p class="mt-1.5 text-xs text-slate-400">
                        Kosongkan untuk menampilkan satu tanggal saja.
                    </p>
                </div>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-violet-700 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-violet-800 focus:outline-none focus:ring-4 focus:ring-violet-200"
                >
                    Terapkan Tanggal
                </button>
            </form>
        </div>
    </div>

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-violet-100 bg-violet-50 px-5 py-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-violet-600">
                Periode aktif
            </p>

            <p class="mt-1 font-bold text-violet-900">
                {{ $periodLabel }}
            </p>
        </div>

        <span class="rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-violet-700 shadow-sm">
            {{ $periodSummary }}
        </span>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">
                        Total Kunjungan
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ number_format($analytics['total_visits'] ?? 0) }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>
            </div>

            <p class="mt-3 text-xs text-slate-400">
                Pembukaan halaman detail produk pada periode ini.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">
                        Pengunjung Unik
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ number_format($analytics['unique_visitors'] ?? 0) }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                    </svg>
                </div>
            </div>

            <p class="mt-3 text-xs text-slate-400">
                Berdasarkan sesi browser yang berbeda.
            </p>
        </div>

        <div class="rounded-2xl border border-green-200 bg-green-50 p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-green-700">
                        Rata-rata per Hari
                    </p>

                    <p class="mt-2 text-3xl font-bold text-green-800">
                        {{ number_format($analytics['average_daily_visits'] ?? 0, 1, ',', '.') }}
                    </p>
                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5 9 7.5l4.5 4.5L21 4.5" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 4.5h6v6" />
                    </svg>
                </div>
            </div>

            <p class="mt-3 text-xs text-green-700/70">
                Total kunjungan dibagi jumlah hari pada periode.
            </p>
        </div>

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-sm text-amber-700">
                        Produk Paling Dikunjungi
                    </p>

                    @if ($mostVisitedProduct)
                        <p
                            class="mt-2 truncate text-lg font-bold text-amber-900"
                            title="{{ $mostVisitedProduct->name }}"
                        >
                            {{ $mostVisitedProduct->name }}
                        </p>

                        <p class="mt-1 text-sm font-semibold text-amber-700">
                            {{ number_format($mostVisitedProduct->period_views_count ?? 0) }} kunjungan
                        </p>
                    @else
                        <p class="mt-2 text-lg font-bold text-amber-900">
                            Belum ada data
                        </p>
                    @endif
                </div>

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m12 3 2.6 5.27 5.82.85-4.21 4.1.99 5.79L12 16.27 6.8 19l.99-5.78-4.21-4.1 5.82-.85L12 3Z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 grid min-w-0 gap-5 xl:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)]">
        <div class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start">
                <div>
                    <h3 class="font-bold text-slate-900">
                        Tren Kunjungan
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Grafik otomatis berubah menjadi per jam, hari, bulan, atau tahun.
                    </p>
                </div>

                <div class="sm:text-right">
                    <p class="text-xs text-slate-400">
                        {{ $periodLabel }}
                    </p>

                    <p class="text-lg font-bold text-violet-700">
                        {{ number_format($trend->sum('visits')) }}
                    </p>
                </div>
            </div>

            <div class="mt-8 w-full max-w-full overflow-x-auto overscroll-x-contain pb-3">
                <div
                    class="flex h-56 items-end gap-2"
                    style="min-width: {{ $chartMinimumWidth }}px"
                >
                    @foreach ($trend as $item)
                        @php
                            $barHeight = $item['visits'] > 0
                                ? max(
                                    8,
                                    round(
                                        ($item['visits'] / $maxTrendVisits) * 100
                                    )
                                )
                                : 3;
                        @endphp

                        <div class="flex min-w-0 flex-1 flex-col items-center justify-end gap-2">
                            <span class="text-xs font-semibold text-slate-600">
                                {{ number_format($item['visits']) }}
                            </span>

                            <div
                                class="flex h-36 w-full items-end rounded-xl bg-slate-100 p-1"
                                title="{{ $item['full_label'] }}: {{ $item['visits'] }} kunjungan, {{ $item['unique_visitors'] }} pengunjung unik"
                            >
                                <div
                                    class="w-full rounded-lg bg-violet-600 transition hover:bg-violet-700"
                                    style="height: {{ $barHeight }}%"
                                ></div>
                            </div>

                            <span
                                class="w-full truncate text-center text-[11px] font-medium text-slate-500"
                                title="{{ $item['full_label'] }}"
                            >
                                {{ $item['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-5">
                <h3 class="font-bold text-slate-900">
                    5 Produk Teratas
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Peringkat produk pada {{ strtolower($periodLabel) }}.
                </p>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($topProducts as $product)
                    @php
                        $productVisits = (int) (
                            $product->period_views_count ?? 0
                        );
                        $percentage = $topProductVisits > 0
                            ? round(
                                ($productVisits / $topProductVisits) * 100
                            )
                            : 0;
                        $canOpenPublicPage = $product->status === 'active'
                            && $product->umkm?->verification_status === 'active';
                    @endphp

                    <div class="flex items-center gap-4 px-6 py-4">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-sm font-bold text-violet-700">
                            {{ $loop->iteration }}
                        </div>

                        <div class="h-12 w-12 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                            @if ($product->main_image)
                                <img
                                    src="{{ asset('storage/'.$product->main_image) }}"
                                    alt="{{ $product->name }}"
                                    class="h-full w-full object-cover"
                                >
                            @else
                                <div class="flex h-full items-center justify-center text-xs font-bold text-slate-400">
                                    IMG
                                </div>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            @if ($canOpenPublicPage)
                                <a
                                    href="{{ route('store.product.show', $product) }}"
                                    target="_blank"
                                    class="block truncate text-sm font-bold text-slate-900 transition hover:text-violet-700"
                                >
                                    {{ $product->name }}
                                </a>
                            @else
                                <p class="truncate text-sm font-bold text-slate-900">
                                    {{ $product->name }}
                                </p>
                            @endif

                            @if ($showUmkm)
                                <p class="mt-0.5 truncate text-xs text-slate-400">
                                    {{ $product->umkm?->business_name ?? 'UMKM tidak tersedia' }}
                                </p>
                            @endif

                            <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full bg-violet-500"
                                    style="width: {{ $percentage }}%"
                                ></div>
                            </div>
                        </div>

                        <div class="shrink-0 text-right">
                            <p class="text-sm font-bold text-slate-900">
                                {{ number_format($productVisits) }}
                            </p>

                            <p class="text-xs text-slate-400">
                                kunjungan
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="font-semibold text-slate-700">
                            Belum ada kunjungan produk
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Pilih periode lain atau buka halaman produk untuk membuat data kunjungan.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>