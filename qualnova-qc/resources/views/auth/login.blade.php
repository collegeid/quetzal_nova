<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Sistem | Qual Nova</title>
    
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }

        .shadow-premium {
            box-shadow: 0 25px 50px -12px rgba(79, 70, 229, 0.1);
        }

        .rounded-custom {
            border-radius: 2.5rem;
        }

        .glass-input {
            background: rgba(248, 250, 252, 0.8);
            backdrop-filter: blur(4px);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .glass-input:focus {
            background: white;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    <div class="absolute top-0 -left-20 w-96 h-96 bg-indigo-200 rounded-full blur-[120px] opacity-50"></div>
    <div class="absolute bottom-0 -right-20 w-96 h-96 bg-purple-200 rounded-full blur-[120px] opacity-50"></div>

    <div class="w-full max-w-[450px] relative z-10">
        
        <a href="/" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-indigo-600 transition-colors mb-8">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Beranda
        </a>

        <div class="bg-white rounded-custom shadow-premium p-10 md:p-12 border border-white">
            
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-3xl shadow-xl shadow-indigo-100 mb-6 transform -rotate-3">
                    <i data-lucide="activity" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tighter italic uppercase leading-none">
                    Qual <span class="text-indigo-600">Nova</span>
                </h1>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-3 italic">Authentication Gateway</p>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-2xl text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-[10px] font-black uppercase text-indigo-500 mb-2 ml-1 tracking-widest">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                            class="glass-input w-full pl-12 pr-4 py-4 rounded-2xl text-sm font-bold text-gray-700 placeholder-gray-300 outline-none" 
                            placeholder="nama@perusahaan.com">
                    </div>
                    @error('email')
                        <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 italic uppercase tracking-tighter">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2 ml-1">
                        <label for="password" class="text-[10px] font-black uppercase text-indigo-500 tracking-widest">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[9px] font-black text-gray-400 hover:text-indigo-600 uppercase tracking-tighter italic">Lupa Sandi?</a>
                        @endif
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input id="password" type="password" name="password" required 
                            class="glass-input w-full pl-12 pr-4 py-4 rounded-2xl text-sm font-bold text-gray-700 placeholder-gray-300 outline-none" 
                            placeholder="••••••••••••">
                    </div>
                    @error('password')
                        <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 italic uppercase tracking-tighter">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center px-1">
                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" name="remember" class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ml-3 text-[10px] font-black text-gray-400 group-hover:text-gray-600 uppercase tracking-widest">Ingat Sesi Ini</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-5 bg-gray-900 hover:bg-indigo-600 text-white rounded-3xl text-xs font-black uppercase tracking-[0.3em] transition-all transform hover:-translate-y-1 active:scale-95 shadow-xl shadow-indigo-100 group">
                    <span class="flex items-center justify-center gap-2">
                        Masuk Sistem 
                        <i data-lucide="chevron-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </button>
            </form>
        </div>

        <p class="text-center mt-10 text-[10px] font-black text-gray-400 uppercase tracking-[0.4em]">
            © {{ date('Y') }} <span class="text-indigo-600 italic underline">Qual Nova</span> — Quetzal Team
        </p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>