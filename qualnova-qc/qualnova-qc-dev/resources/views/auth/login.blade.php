<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | QC Cacat Kain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-600 min-h-screen flex items-center justify-center px-4">

    <div class="bg-white/10 backdrop-blur-md border border-white/20 shadow-2xl rounded-2xl p-8 w-full max-w-md text-white">
        <div class="text-center mb-8">
            <i data-lucide="activity" class="w-12 h-12 mx-auto text-white mb-2"></i>
            <h1 class="text-2xl font-bold tracking-wide">Qual Nova | Quetzal Team's</h1>
            <p class="text-sm text-blue-100">Masuk ke sistem monitoring cacat kain</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="bg-green-500/20 text-green-100 text-sm p-3 rounded mb-4 text-center">
                {{ session('status') }}
            </div>
        @endif

        <!-- 🔐 Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium mb-1">Email</label>
                <div class="flex items-center bg-white/20 rounded-lg px-3 py-2">
                    <i data-lucide="mail" class="w-5 h-5 text-blue-100 mr-2"></i>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="bg-transparent w-full text-white placeholder-blue-200 focus:outline-none" placeholder="Masukkan email">
                </div>
                @error('email')
                    <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium mb-1">Password</label>
                <div class="flex items-center bg-white/20 rounded-lg px-3 py-2">
                    <i data-lucide="lock" class="w-5 h-5 text-blue-100 mr-2"></i>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="bg-transparent w-full text-white placeholder-blue-200 focus:outline-none" placeholder="Masukkan password">
                </div>
                @error('password')
                    <p class="text-red-200 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-sm mt-3">
                <label class="flex items-center space-x-2">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="rounded bg-white/10 border-white/30 text-blue-400 focus:ring-blue-400">
                    <span>Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-blue-200 hover:text-white underline">
                        Lupa password?
                    </a>
                @endif
            </div>

            <!-- Tombol Login -->
            <button type="submit" 
                class="w-full mt-4 bg-white text-blue-600 font-semibold py-3 rounded-lg hover:bg-blue-100 transition">
                Masuk Sekarang
            </button>

          
        </form>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
