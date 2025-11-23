<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>Login - Kelola Uang</title>
    @vite('resources/css/app.css')
</head>
<body class="h-full flex items-center justify-center bg-gray-100 dark:bg-gray-900">
    
    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 animate-fade-in">
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-100 mb-6">
            Masuk ke <span class="text-sky-600">Kelola Uang</span>
        </h2>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 dark:bg-red-700 text-red-700 dark:text-white px-4 py-2 text-sm">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('auth.send-code') }}" class="space-y-6">
            @csrf

            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                Email
            </label>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-gray-900 dark:text-gray-100 dark:bg-gray-700"
                   placeholder="example@gmail.com"
            >

            <button type="submit"
                class="w-full bg-sky-600 hover:bg-sky-700 text-white font-medium rounded-lg py-2 transition">
                Kirim Kode Verifikasi
            </button>
        </form>
    </div>

</body>

</html>
