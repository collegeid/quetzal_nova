<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qual Nova | Quality Control System</title>
    
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .shadow-premium {
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
        }

        .rounded-custom {
            border-radius: 2rem;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        [x-cloak] { display: none !important; }

        .hover-up {
            transition: all 0.3s ease;
        }
        .hover-up:hover {
            transform: translateY(-10px);
        }

        /* Video Area Styling */
        .video-wrapper {
            position: relative;
            cursor: pointer;
            overflow: hidden;
            background: #000;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .video-wrapper:hover {
            box-shadow: 0 30px 60px -12px rgba(79, 70, 229, 0.3);
        }
        
        .video-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.4) 100%);
            z-index: 5;
        }
    </style>
</head>

<body class="antialiased selection:bg-indigo-100 selection:text-indigo-700" x-data="{ videoOpen: false }">

    <nav class="sticky top-0 z-50 transition-all duration-300 glass border-b border-gray-100">
        <div class="max-w-7xl mx-auto flex justify-between items-center py-5 px-6 lg:px-8">
            <div class="flex items-center gap-2">
                <div class="bg-indigo-600 p-2 rounded-xl shadow-lg shadow-indigo-200">
                    <i data-lucide="activity" class="w-6 h-6 text-white"></i>
                </div>
                <h1 class="text-xl font-black tracking-tighter italic uppercase text-gray-900">
                    Qual <span class="text-indigo-600">Nova</span>
                </h1>
            </div>

            <nav class="space-x-8 hidden md:flex">
                <a href="#fitur" class="text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-indigo-600 transition">Fitur</a>
                <a href="#tentang" class="text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-indigo-600 transition">Tim</a>
                <a href="#kontak" class="text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-indigo-600 transition">Kontak</a>
            </nav>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-gray-900 text-white px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 shadow-xl transition-all active:scale-95">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-8 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all active:scale-95">
                        Masuk Sistem
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <section class="relative overflow-hidden pt-20 pb-32">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row items-center gap-16">
            
            <div class="md:w-1/2 text-center md:text-left">
                <div class="inline-block px-4 py-1.5 mb-6 bg-indigo-50 border border-indigo-100 rounded-full">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-600">Advanced QC Platform</p>
                </div>
                <h2 class="text-5xl md:text-7xl font-black text-gray-900 mb-8 leading-[1.1] tracking-tighter italic uppercase text-left">
                    Pantau <br> <span class="text-indigo-600">Kualitas</span> <br> Lebih Detail.
                </h2>
                <p class="text-lg text-gray-500 font-medium max-w-xl mb-10 leading-relaxed text-left">
                    Transformasi digital untuk tim QC kain. Lihat bagaimana sistem kami bekerja mengidentifikasi kecacatan secara <span class="text-gray-900 font-bold underline decoration-indigo-500 italic uppercase">Real-Time</span> melalui demo video.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center bg-gray-900 text-white px-10 py-5 rounded-[1.5rem] text-xs font-black uppercase tracking-widest hover:bg-indigo-600 shadow-2xl transition-all">
                        Mulai Monitoring
                    </a>
                    <button @click="videoOpen = true" class="inline-flex items-center justify-center bg-white border border-gray-200 text-gray-900 px-10 py-5 rounded-[1.5rem] text-xs font-black uppercase tracking-widest hover:bg-gray-50 shadow-sm transition-all gap-2 group">
                        <i data-lucide="play" class="w-4 h-4 text-indigo-600 group-hover:scale-110 transition-transform"></i> Tonton Demo
                    </button>
                </div>
            </div>

            <div class="md:w-1/2 relative w-full group">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-indigo-200 rounded-full blur-[80px] opacity-40"></div>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-purple-200 rounded-full blur-[80px] opacity-40"></div>

                <div @click="videoOpen = true" class="relative z-10 video-wrapper rounded-custom border-[6px] border-white shadow-premium transform md:rotate-2 hover:rotate-0 transition-all duration-700 ease-out aspect-video">
                    <div class="video-overlay"></div>
                    
                    <div class="absolute inset-0 flex items-center justify-center z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="bg-white/90 backdrop-blur-md p-5 rounded-full shadow-2xl scale-75 group-hover:scale-100 transition-transform duration-500">
                            <i data-lucide="maximize" class="w-6 h-6 text-indigo-600"></i>
                        </div>
                    </div>

                    <video autoplay muted loop playsinline class="w-full h-full object-cover">
                        <source src="{{ asset('storage/videos/demo.mp4') }}" type="video/mp4">
                        <source src="https://assets.mixkit.co/videos/preview/mixkit-software-developer-working-on-code-screen-close-up-1728-large.mp4" type="video/mp4">
                    </video>

                    <div class="absolute bottom-6 right-6 z-10 bg-indigo-600 px-3 py-1.5 rounded-lg shadow-lg">
                        <p class="text-[8px] font-black text-white uppercase tracking-widest italic">Live Preview</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div x-show="videoOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-gray-900/90 backdrop-blur-2xl" 
         x-cloak>
        
        <div @click.away="videoOpen = false" 
             x-show="videoOpen"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 scale-75 translate-y-10"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             class="relative w-full max-w-5xl bg-black rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden">
            
            <button @click="videoOpen = false" class="absolute top-6 right-6 z-[110] bg-white/10 hover:bg-rose-500 p-4 rounded-2xl text-white transition-all active:scale-90 group">
                <i data-lucide="x" class="w-6 h-6 group-hover:rotate-90 transition-transform"></i>
            </button>

            <div class="aspect-video w-full">
                <video x-ref="player" :src="videoOpen ? '{{ asset('storage/videos/demo.mp4') }}' : ''" 
                       class="w-full h-full" controls autoplay>
                </video>
            </div>

            <div class="p-8 bg-gradient-to-t from-gray-900 to-transparent flex justify-between items-end">
                <div>
                    <h4 class="text-2xl font-black text-white italic uppercase tracking-tighter">Sistem Walkthrough</h4>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Qual Nova Core Engine v1.0</p>
                </div>
                <div class="bg-indigo-600/20 border border-indigo-500/30 px-4 py-2 rounded-xl text-indigo-400 text-[9px] font-black uppercase tracking-widest">
                    High Definition 1080p
                </div>
            </div>
        </div>
    </div>

    <section id="fitur" class="py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center text-left">
            <h3 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.4em] mb-4 text-center">Core Ecosystem</h3>
            <h2 class="text-4xl font-black text-gray-900 uppercase italic mb-20 tracking-tighter text-center">Keunggulan <span class="text-indigo-600">Qual Nova</span></h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-12 rounded-custom border border-gray-100 shadow-premium hover-up">
                    <div class="w-16 h-16 bg-indigo-600 rounded-2xl shadow-lg flex items-center justify-center mb-8 mx-auto"><i data-lucide="shield-check" class="text-white w-8 h-8"></i></div>
                    <h4 class="text-xl font-black text-gray-900 uppercase italic mb-4 tracking-tight">Verifikasi Presisi</h4>
                    <p class="text-sm font-bold text-gray-400 leading-relaxed">Pencatatan data cacat terverifikasi dengan alur validasi bertingkat antara Operator & QC.</p>
                </div>
                <div class="bg-gray-50 p-12 rounded-custom border border-gray-100 shadow-premium hover-up">
                    <div class="w-16 h-16 bg-indigo-600 rounded-2xl shadow-lg flex items-center justify-center mb-8 mx-auto"><i data-lucide="bar-chart-3" class="text-white w-8 h-8"></i></div>
                    <h4 class="text-xl font-black text-gray-900 uppercase italic mb-4 tracking-tight">Analisis Statistik</h4>
                    <p class="text-sm font-bold text-gray-400 leading-relaxed text-center">Visualisasi tren, performa mesin, dan distribusi jenis cacat dalam grafik harian.</p>
                </div>
                <div class="bg-gray-50 p-12 rounded-custom border border-gray-100 shadow-premium hover-up">
                    <div class="w-16 h-16 bg-indigo-600 rounded-2xl shadow-lg flex items-center justify-center mb-8 mx-auto"><i data-lucide="fingerprint" class="text-white w-8 h-8"></i></div>
                    <h4 class="text-xl font-black text-gray-900 uppercase italic mb-4 tracking-tight text-center text-center">Role Authority</h4>
                    <p class="text-sm font-bold text-gray-400 leading-relaxed text-center text-center">Pembagian hak akses yang ketat antara Operator, QC, Manager, hingga Super Admin.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="tentang" class="py-32 bg-gray-50 text-center">
        <div class="max-w-7xl mx-auto px-6">
            <h3 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.4em] mb-4 text-center">Development Crew</h3>
            <h2 class="text-4xl font-black text-gray-900 uppercase italic mb-20 tracking-tighter text-center">Engineered by <span class="text-indigo-600">Quetzal Team</span></h2>
            
            <div class="flex flex-col items-center gap-12 text-center">
                <div class="w-full max-w-sm bg-white p-10 rounded-custom shadow-premium border-2 border-indigo-600 relative hover-up">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-indigo-600 text-white px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest italic shadow-lg">Team Lead</div>
                    <div class="w-24 h-24 bg-indigo-600 rounded-full mx-auto mb-6 flex items-center justify-center text-white font-black text-2xl italic shadow-xl shadow-indigo-100 border-4 border-white">FD</div>
                    <h4 class="text-xl font-black text-gray-900 uppercase italic leading-none text-center">Febriansah Dirgantara</h4>
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mt-3 text-center">Project Manager & Lead Programmer</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 w-full">
                    <div class="bg-white p-10 rounded-custom shadow-premium border border-white hover-up">
                        <div class="w-20 h-20 bg-gray-100 rounded-full mx-auto mb-6 flex items-center justify-center text-gray-400 font-black text-xl italic uppercase">RM</div>
                        <h4 class="text-lg font-black text-gray-900 uppercase italic leading-none">Rizal Maulana</h4>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">Team Member</p>
                    </div>
                    <div class="bg-white p-10 rounded-custom shadow-premium border border-white hover-up">
                        <div class="w-20 h-20 bg-gray-100 rounded-full mx-auto mb-6 flex items-center justify-center text-gray-400 font-black text-xl italic uppercase">RF</div>
                        <h4 class="text-lg font-black text-gray-900 uppercase italic leading-none">Rifqii Fauzi A.</h4>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">Team Member</p>
                    </div>
                    <div class="bg-white p-10 rounded-custom shadow-premium border border-white hover-up">
                        <div class="w-20 h-20 bg-gray-100 rounded-full mx-auto mb-6 flex items-center justify-center text-gray-400 font-black text-xl italic uppercase">FL</div>
                        <h4 class="text-lg font-black text-gray-900 uppercase italic leading-none">Fazri Lukman</h4>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">Team Member</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="kontak" class="bg-white py-32 text-center">
        <div class="max-w-4xl mx-auto px-6">
            <div class="bg-indigo-600 rounded-[3rem] p-16 shadow-2xl relative overflow-hidden text-center">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
                <h3 class="text-3xl font-black text-white uppercase italic tracking-tighter mb-6 relative z-10 text-center">Siap Mengoptimalkan <br> Kualitas Produksi?</h3>
                <p class="text-indigo-100 font-bold mb-10 relative z-10 opacity-80 uppercase text-xs tracking-widest text-center">Hubungi langsung Team Lead kami melalui LinkedIn.</p>
                <a href="https://www.linkedin.com/in/febrid" target="_blank" class="inline-flex items-center gap-3 bg-white text-indigo-600 px-10 py-5 rounded-[1.5rem] text-xs font-black uppercase tracking-widest hover:bg-gray-50 transition-all shadow-xl"><i data-lucide="linkedin" class="w-4 h-4"></i> Hubungi Febriansah Dirgantara</a>
            </div>
        </div>
    </section>

    <footer class="py-12 text-center border-t border-gray-100 bg-[#f8fafc]">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.4em]">
            &copy; {{ date('Y') }} <span class="text-indigo-600 italic underline">Qual Nova QC System</span> — Developed by Quetzal Team
        </p>
    </footer>

    <script>
        lucide.createIcons();
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-premium', 'py-4');
                nav.classList.remove('py-5');
            } else {
                nav.classList.remove('shadow-premium', 'py-4');
                nav.classList.add('py-5');
            }
        });
    </script>
</body>
</html>