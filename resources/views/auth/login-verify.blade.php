<!DOCTYPE html>
<html lang="id" class="h-full dark">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Kode - Kelola Uang</title>
    @vite('resources/css/app.css')
</head>

<body class="h-full flex items-center justify-center bg-gray-100 dark:bg-gray-900">

    <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-xl rounded-xl p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-gray-100 mb-6">
            Verifikasi Email
        </h2>

        <p class="text-center mb-4 text-gray-600 dark:text-gray-400 text-sm">
            Kode verifikasi telah dikirim ke:
            <span class="font-semibold">{{ $email }}</span>
        </p>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 dark:bg-red-700 text-red-700 dark:text-white px-4 py-2 text-sm">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('auth.verify-code') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">

            {{-- Input OTP 6 digit --}}
            <div class="flex justify-center gap-2">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" maxlength="1" name="code[]"
                        class="w-12 h-12 text-center text-xl font-bold border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-sky-500 dark:bg-gray-700 dark:text-white"
                        required>
                @endfor
            </div>

            <button type="submit"
                class="w-full bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-lg py-2 mt-4 transition">
                Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('auth.send-code') }}" class="text-center mt-4">
            @csrf
            <button type="submit"
                class="text-sky-600 dark:text-sky-400 font-medium hover:underline text-sm">
                Kirim ulang kode
            </button>
        </form>

    </div>

    <script>
        // Auto-focus & auto-next OTP input
        const inputs = document.querySelectorAll('input[name="code[]"]');
        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
        });
        inputs[0].focus();
    </script>

</body>
</html>
