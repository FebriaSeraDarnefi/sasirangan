<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — SasiVerse</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-indigo-700 text-white px-6 py-4 flex justify-between items-center">
        <span class="font-bold text-lg">SasiVerse — Admin</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-white text-indigo-700 px-4 py-1 rounded text-sm font-semibold">
                Keluar
            </button>
        </form>
    </nav>
    <div class="max-w-4xl mx-auto mt-10 px-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Dashboard Admin</h1>
        <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}!</p>
    </div>
</body>
</html>