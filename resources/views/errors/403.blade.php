<!DOCTYPE html>
<html lang="id" class="h-full bg-appbg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak | TaskFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-6 text-center">
    <div class="max-w-md bg-surface p-8 rounded-2xl border border-gray-100 shadow-md space-y-4">
        <div class="w-16 h-16 mx-auto rounded-2xl bg-red-50 text-danger flex items-center justify-center font-bold text-2xl">
            403
        </div>
        <h1 class="text-xl font-bold text-textmain">Akses Ditolak</h1>
        <p class="text-xs text-textsec leading-relaxed">
            Maaf, Anda tidak memiliki izin atau otorisasi yang sah untuk melihat atau memodifikasi data ini.
        </p>
        <div class="pt-2">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary-600 text-white text-xs font-semibold rounded-xl transition">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</body>
</html>
