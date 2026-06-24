<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — SasiVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-amber-600 text-white px-6 py-4 flex justify-between items-center">
        <span class="font-bold text-lg">SasiVerse</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-white text-amber-600 px-4 py-1 rounded text-sm font-semibold">
                Keluar
            </button>
        </form>
    </nav>
    <div class="max-w-4xl mx-auto mt-10 px-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang di SasiVerse</h1>
        <p class="text-gray-600">Halo, {{ auth()->user()->name }}!</p>
    </div>
</body>
</html>