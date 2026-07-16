@extends('layouts.store')

@section('title', 'Edukasi Sasirangan')

@section('content')
    {{-- Hero edukasi --}}
    <section class="relative overflow-hidden bg-slate-950">
        <div class="absolute inset-0">
            <div class="absolute -left-28 top-10 h-80 w-80 rounded-full bg-violet-700/30 blur-3xl"></div>
            <div class="absolute -right-24 bottom-0 h-80 w-80 rounded-full bg-fuchsia-600/20 blur-3xl"></div>
        </div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8 lg:py-20">
            <div>
                <span class="inline-flex rounded-full border border-violet-400/30 bg-violet-500/10 px-4 py-2 text-sm font-semibold text-violet-200">
                    Edukasi Budaya Banjar
                </span>

                <h1 class="mt-6 text-4xl font-bold leading-tight text-white sm:text-5xl">
                    Sejarah dan perjalanan
                    <span class="text-violet-300">kain Sasirangan</span>
                </h1>

                <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                    Mengenal asal-usul kain adat suku Banjar, kisah Putri
                    Junjung Buih, fungsi kain Pamintan, serta proses pembuatan
                    Sasirangan sebagai warisan budaya Kalimantan Selatan.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a
                        href="#sejarah-sasirangan"
                        class="rounded-xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-violet-500"
                    >
                        Baca Sejarah
                    </a>

                    <a
                        href="#video-pembuatan"
                        class="rounded-xl border border-white/20 bg-white/10 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/20"
                    >
                        Tonton Video Pembuatan
                    </a>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-[2rem] border border-white/15 shadow-2xl shadow-violet-950/40">
                <img
                    src="{{ asset('images/sasirangan-hero.jpg') }}"
                    alt="Kain Sasirangan sebagai warisan budaya Kalimantan Selatan"
                    class="aspect-[4/3] h-full w-full object-cover"
                >

                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/10 to-transparent"></div>

                <div class="absolute inset-x-0 bottom-0 p-6">
                    <p class="text-xl font-bold text-white">
                        Warisan sejak masa Kerajaan Negara Dipa
                    </p>

                    <p class="mt-2 text-sm leading-6 text-slate-200">
                        Dari kain Lagundi dan Pamintan hingga dikenal sebagai
                        kain Sasirangan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Navigasi isi --}}
    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl gap-3 overflow-x-auto px-4 py-5 sm:px-6 lg:px-8">
            <a href="#sejarah-sasirangan" class="shrink-0 rounded-full bg-violet-50 px-4 py-2 text-sm font-semibold text-violet-700 hover:bg-violet-100">
                Sejarah Sasirangan
            </a>

            <a href="#kain-pamintan" class="shrink-0 rounded-full bg-violet-50 px-4 py-2 text-sm font-semibold text-violet-700 hover:bg-violet-100">
                Kain Pamintan
            </a>

            <a href="#makna-warna" class="shrink-0 rounded-full bg-violet-50 px-4 py-2 text-sm font-semibold text-violet-700 hover:bg-violet-100">
                Makna Warna
            </a>

            <a href="#video-pembuatan" class="shrink-0 rounded-full bg-violet-50 px-4 py-2 text-sm font-semibold text-violet-700 hover:bg-violet-100">
                Video Pembuatan
            </a>
        </div>
    </section>

    <section id="sejarah-sasirangan" class="scroll-mt-32">
        <div class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-10">
                <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                    Sejarah Sasirangan
                </p>

                <h2 class="mt-3 text-3xl font-bold text-slate-900">
                    Dari kain Lagundi menjadi kain Sasirangan
                </h2>

                <div class="mt-7 space-y-6 text-justify text-base leading-8 text-slate-600">
                    <p>
                        Kain Sasirangan merupakan kain adat suku Banjar di
                        Kalimantan Selatan yang diwariskan secara turun-temurun
                        sejak abad ke-12. Pada awalnya, kain tersebut dikenal
                        dengan nama kain Lagundi dan digunakan sebagai sarana
                        pengobatan. Karena pembuatannya dilakukan berdasarkan
                        permintaan orang yang memerlukan, kain ini kemudian juga
                        disebut kain Pamintan, yaitu kain yang dibuat sesuai
                        permintaan. Masyarakat pada masa itu mempercayai bahwa
                        kain Pamintan memiliki kekuatan magis yang dapat membantu
                        menyembuhkan orang sakit.
                    </p>

                    <p>
                        Berdasarkan Hikayat Banjar, Patih Lambung Mangkurat
                        diriwayatkan bertapa di atas lanting atau rakit dengan
                        cara <em>belarut banyu</em>, yaitu mengikuti arus air
                        sungai. Mendekati akhir masa pertapaannya, Patih tiba di
                        daerah Rantau Kota Bagantung. Di tempat tersebut, ia
                        melihat segumpal buih berwarna putih dan mendengar suara
                        seorang perempuan dari dalam buih. Perempuan itu adalah
                        Putri Junjung Buih.
                    </p>

                    <p>
                        Patih Lambung Mangkurat kemudian berniat menjadikan Putri
                        Junjung Buih sebagai putri di Kerajaan Negara Dipa.
                        Putri Junjung Buih bersedia memenuhi keinginan tersebut
                        dengan beberapa persyaratan. Patih diminta membangun
                        Istana Batung atau mahligai megah dalam waktu satu hari
                        oleh 40 laki-laki yang masih lajang. Selain itu, ia juga
                        diminta membuat sehelai kain Lagundi berwarna kuning yang
                        sekarang dikenal sebagai kain Sasirangan.
                    </p>

                    <p>
                        Kain tersebut juga harus selesai dalam waktu satu hari.
                        Proses pembuatannya dilakukan dengan cara disirang dan
                        diwarnai oleh 40 perempuan yang masih perawan, dengan
                        menggunakan motif Padiwaringin. Seluruh permintaan Putri
                        Junjung Buih, yaitu Istana Batung dan kain Lagundi
                        berwarna kuning, berhasil dipenuhi oleh Patih Lambung
                        Mangkurat.
                    </p>

                    <p>
                        Pada waktu yang telah disepakati, Putri Junjung Buih
                        berpindah dari alam sebelumnya di dasar Sungai Tabalong
                        menuju alam manusia dan menempati Istana Batung. Sejak
                        saat itu, kain Lagundi ditetapkan sebagai kain kebesaran
                        yang dikenakan oleh keluarga kerajaan. Dalam
                        perkembangannya, nama Sasirangan berkaitan dengan proses
                        pembuatannya, yaitu kain yang dijelujur, diikat, atau
                        disirang sebelum melalui proses pewarnaan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Fungsi kain pamintan --}}
    <section id="kain-pamintan" class="scroll-mt-32 bg-violet-50">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[0.85fr_1.15fr]">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                        Kain Pamintan
                    </p>

                    <h2 class="mt-3 text-3xl font-bold text-slate-900">
                        Fungsi tradisional dan kepercayaan masyarakat Banjar
                    </h2>

                    <p class="mt-5 text-base leading-8 text-slate-600">
                        Selain menjadi kain kebesaran, kain Lagundi atau kain
                        Pamintan dibuat dalam bentuk sederhana dan digunakan
                        sesuai kebutuhan pemakainya. Kain ini dipercaya memiliki
                        nilai magis untuk pengobatan, perlindungan dari gangguan
                        makhluk halus, serta mengatasi penyakit yang disebut
                        <em>pingitan</em>. Karena fungsi tersebut, kain ini juga
                        dikenal sebagai kain untuk <em>batatamba</em> atau
                        penyembuhan penyakit.
                    </p>

                    <p class="mt-5 text-base leading-8 text-slate-600">
                        Untuk memperoleh kain sebagai sarana batatamba, seseorang
                        harus memesan atau memintanya terlebih dahulu. Bentuk
                        kain, motif, warna, dan penggunaannya disesuaikan dengan
                        tujuan atau penyakit yang ingin ditangani.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    @php
                        $pamintanUses = [
                            [
                                'title' => 'Babat atau sabuk',
                                'description' => 'Dililitkan pada bagian perut dan dipercaya digunakan untuk membantu mengatasi diare atau sakit perut.',
                            ],
                            [
                                'title' => 'Laung atau ikat kepala',
                                'description' => 'Dililitkan di kepala dan dipercaya digunakan untuk membantu mengatasi sakit kepala sebelah atau migrain.',
                            ],
                            [
                                'title' => 'Tapih bahalai atau sarung',
                                'description' => 'Digunakan sebagai selimut dan dipercaya membantu pengobatan demam serta gatal-gatal.',
                            ],
                            [
                                'title' => 'Kakamban atau selendang',
                                'description' => 'Digunakan sebagai penutup kepala dan dipercaya membantu mengatasi sakit kepala.',
                            ],
                        ];
                    @endphp

                    @foreach ($pamintanUses as $use)
                        <article class="rounded-3xl border border-violet-100 bg-white p-6 shadow-sm">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>

                            <h3 class="mt-5 text-lg font-bold text-slate-900">
                                {{ $use['title'] }}
                            </h3>

                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                {{ $use['description'] }}
                            </p>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 rounded-3xl border border-amber-200 bg-amber-50 p-6 text-sm leading-7 text-amber-900">
                Dalam catatan Redho (2016), kain Pamintan berbentuk laung
                dipakai oleh laki-laki yang sakit pada sore hari menjelang
                matahari tenggelam. Waktu pemakaiannya digambarkan sebanding
                dengan lama menghabiskan satu batang rokok atau lamanya seorang
                perempuan menginang, dan digunakan pada hari Rabu menjelang
                malam Kamis. Penjelasan ini merupakan bagian dari kepercayaan
                dan praktik tradisional masyarakat pada masa lalu, bukan
                pengganti pemeriksaan atau pengobatan medis.
            </div>
        </div>
    </section>

    {{-- Makna warna --}}
    <section id="makna-warna" class="scroll-mt-32">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-violet-700">
                    Warna Kain Pamintan
                </p>

                <h2 class="mt-3 text-3xl font-bold text-slate-900">
                    Peruntukan warna dalam tradisi masyarakat
                </h2>

                <p class="mt-5 text-base leading-8 text-slate-600">
                    Pada zaman dahulu, warna kain Pamintan dipilih berdasarkan
                    tujuan penggunaannya. Menurut Ganie (2016), beberapa warna
                    dipercaya memiliki peruntukan sebagai berikut.
                </p>
            </div>

            @php
                $colorMeanings = [
                    ['name' => 'Kuning', 'class' => 'bg-yellow-400', 'text' => 'Dipercaya digunakan untuk penyakit kuning atau orang yang terkena wisa.'],
                    ['name' => 'Merah', 'class' => 'bg-red-500', 'text' => 'Dipercaya digunakan untuk migrain dan insomnia atau sulit tidur.'],
                    ['name' => 'Hijau', 'class' => 'bg-emerald-500', 'text' => 'Dipercaya digunakan untuk stroke atau kelumpuhan.'],
                    ['name' => 'Hitam', 'class' => 'bg-slate-900', 'text' => 'Dipercaya digunakan untuk demam dan kulit gatal-gatal.'],
                    ['name' => 'Ungu', 'class' => 'bg-violet-600', 'text' => 'Dipercaya digunakan untuk diare, disentri, dan kolera.'],
                    ['name' => 'Cokelat', 'class' => 'bg-amber-800', 'text' => 'Dipercaya digunakan untuk gangguan kejiwaan.'],
                ];
            @endphp

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($colorMeanings as $color)
                    <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div class="h-3 {{ $color['class'] }}"></div>

                        <div class="p-6">
                            <div class="flex items-center gap-3">
                                <span class="h-5 w-5 rounded-full {{ $color['class'] }} ring-4 ring-slate-100"></span>

                                <h3 class="text-lg font-bold text-slate-900">
                                    {{ $color['name'] }}
                                </h3>
                            </div>

                            <p class="mt-4 text-sm leading-7 text-slate-600">
                                {{ $color['text'] }}
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>

            <p class="mt-7 text-center text-sm leading-7 text-slate-500">
                Informasi tersebut disajikan sebagai pengetahuan sejarah dan
                budaya. Keluhan kesehatan tetap perlu ditangani oleh tenaga
                kesehatan yang kompeten.
            </p>
        </div>
    </section>

    {{-- Video --}}
    <section id="video-pembuatan" class="scroll-mt-32 bg-slate-950">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid items-center gap-10 lg:grid-cols-[0.8fr_1.2fr]">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wider text-violet-300">
                        Video Edukasi
                    </p>

                    <h2 class="mt-3 text-3xl font-bold text-white">
                        Proses pembuatan kain Sasirangan
                    </h2>

                    <p class="mt-5 text-base leading-8 text-slate-300">
                        Saksikan tahapan pembuatan kain Sasirangan melalui video
                        YouTube berikut, mulai dari persiapan kain, proses
                        menyirang, pewarnaan, hingga menjadi kain bermotif khas
                        Kalimantan Selatan.
                    </p>

                    <a
                        href="https://youtu.be/Ri2B5_T7PWw?si=zgoITOW6ahPyjNGU"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-7 inline-flex items-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-500"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                            <path d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.254-.42 4.814a2.504 2.504 0 0 1-1.768 1.768C18.254 19 12 19 12 19s-6.254 0-7.812-.418a2.504 2.504 0 0 1-1.768-1.768C2 15.254 2 12 2 12s0-3.254.42-4.814a2.504 2.504 0 0 1 1.768-1.768C5.746 5 12 5 12 5s6.254 0 7.812.418ZM10 9.5v5l4.5-2.5L10 9.5Z" />
                        </svg>

                        Buka Langsung di YouTube
                    </a>
                </div>

                <div class="overflow-hidden rounded-[2rem] border border-white/10 bg-black shadow-2xl shadow-violet-950/50">
                    <div class="aspect-video">
                        <iframe
                            class="h-full w-full"
                            src="https://www.youtube.com/embed/Ri2B5_T7PWw"
                            title="Video proses pembuatan kain Sasirangan"
                            loading="lazy"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Catatan sumber --}}
    <section class="bg-white">
        <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                <h2 class="font-bold text-slate-900">
                    Catatan sumber
                </h2>

                <p class="mt-3 text-sm leading-7 text-slate-600">
                    Narasi sejarah dan fungsi tradisional pada halaman ini
                    disusun berdasarkan materi yang memuat rujukan Redho (2016)
                    dan Ganie (2016). Informasi mengenai pengobatan disajikan
                    sebagai bagian dari pengetahuan budaya dan kepercayaan
                    masyarakat Banjar pada masa lalu.
                </p>
            </div>
        </div>
    </section>
@endsection
