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

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="antialiased selection:bg-indigo-100 selection:text-indigo-700">

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
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-600">Next-Gen QC Platform</p>
                </div>
                <h2 class="text-5xl md:text-7xl font-black text-gray-900 mb-8 leading-[1.1] tracking-tighter italic uppercase">
                    Sistem QC <br> <span class="text-indigo-600">Cacat Kain</span>
                </h2>
                <p class="text-lg text-gray-500 font-medium max-w-xl mb-10 leading-relaxed">
                    Platform cerdas untuk memantau, mencatat, dan menganalisis klasifikasi kecacatan produksi secara <span class="text-gray-900 font-bold underline decoration-indigo-500">Real-Time</span> dan transparan.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center bg-gray-900 text-white px-10 py-5 rounded-[1.5rem] text-xs font-black uppercase tracking-widest hover:bg-indigo-600 shadow-2xl transition-all">
                        Mulai Monitoring
                    </a>
                    <a href="#fitur" class="inline-flex items-center justify-center bg-white border border-gray-200 text-gray-900 px-10 py-5 rounded-[1.5rem] text-xs font-black uppercase tracking-widest hover:bg-gray-50 shadow-sm transition-all">
                        Pelajari Fitur
                    </a>
                </div>
            </div>

            <div class="md:w-1/2 relative w-full" x-data="{ 
                activeSlide: 0, 
                slides: [
                    '{{ asset('storage/images/1.png') }}',
                    '{{ asset('storage/images/2.png') }}',
                    '{{ asset('storage/images/3.png') }}'
                ],
                next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
                prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length },
                init() { setInterval(() => this.next(), 5000) }
            }">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-indigo-200 rounded-full blur-[80px] opacity-50"></div>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-purple-200 rounded-full blur-[80px] opacity-50"></div>

                <div class="relative z-10 group">
                    <div class="overflow-hidden rounded-custom shadow-premium border border-white transform md:rotate-2 bg-white aspect-video">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="activeSlide === index" 
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute inset-0">
                                <img :src="slide" alt="Mockup" class="w-full h-full object-cover" 
                                     onerror="this.src='https://placehold.co/800x450?text=Mockup+Image+'+(index+1)">
                            </div>
                        </template>
                    </div>

                    <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 backdrop-blur-md p-3 rounded-2xl shadow-xl text-indigo-600 opacity-0 group-hover:opacity-100 transition-all hover:bg-white active:scale-90 z-20 border border-indigo-50">
                        <i data-lucide="chevron-left"></i>
                    </button>
                    <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 backdrop-blur-md p-3 rounded-2xl shadow-xl text-indigo-600 opacity-0 group-hover:opacity-100 transition-all hover:bg-white active:scale-90 z-20 border border-indigo-50">
                        <i data-lucide="chevron-right"></i>
                    </button>

                    <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="activeSlide = index" 
                                    :class="activeSlide === index ? 'w-8 bg-indigo-600' : 'w-2 bg-gray-300'"
                                    class="h-2 rounded-full transition-all duration-300"></button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-32 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <h3 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.4em] mb-4">Core Ecosystem</h3>
            <h2 class="text-4xl font-black text-gray-900 uppercase italic mb-20 tracking-tighter">Keunggulan <span class="text-indigo-600">Qual Nova</span></h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-12 rounded-custom shadow-premium hover-up border border-gray-100">
                    <div class="w-16 h-16 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-100 flex items-center justify-center mb-8 mx-auto">
                        <i data-lucide="shield-check" class="text-white w-8 h-8"></i>
                    </div>
                    <h4 class="text-xl font-black text-gray-900 uppercase italic mb-4 tracking-tight">Verifikasi Presisi</h4>
                    <p class="text-sm font-bold text-gray-400 leading-relaxed">Pencatatan data cacat terverifikasi dengan alur validasi bertingkat antara Operator & QC.</p>
                </div>
                <div class="bg-gray-50 p-12 rounded-custom shadow-premium hover-up border border-gray-100">
                    <div class="w-16 h-16 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-100 flex items-center justify-center mb-8 mx-auto">
                        <i data-lucide="bar-chart-3" class="text-white w-8 h-8"></i>
                    </div>
                    <h4 class="text-xl font-black text-gray-900 uppercase italic mb-4 tracking-tight">Analisis Statistik</h4>
                    <p class="text-sm font-bold text-gray-400 leading-relaxed">Visualisasi tren, performa mesin, dan distribusi jenis cacat dalam grafik harian.</p>
                </div>
                <div class="bg-gray-50 p-12 rounded-custom shadow-premium hover-up border border-gray-100">
                    <div class="w-16 h-16 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-100 flex items-center justify-center mb-8 mx-auto">
                        <i data-lucide="fingerprint" class="text-white w-8 h-8"></i>
                    </div>
                    <h4 class="text-xl font-black text-gray-900 uppercase italic mb-4 tracking-tight">Role Authority</h4>
                    <p class="text-sm font-bold text-gray-400 leading-relaxed">Pembagian hak akses yang ketat antara Operator, QC, Manager, hingga Super Admin.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="tentang" class="py-32 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h3 class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.4em] mb-4">Development Crew</h3>
            <h2 class="text-4xl font-black text-gray-900 uppercase italic mb-20 tracking-tighter">Engineered by <span class="text-indigo-600">Quetzal Team</span></h2>
            
            <div class="flex flex-col items-center gap-12">
                <div class="w-full max-w-sm bg-white p-10 rounded-custom shadow-premium border-2 border-indigo-600 relative hover-up">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-indigo-600 text-white px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest italic shadow-lg">Team Lead</div>
                    <div class="w-24 h-24 bg-indigo-600 rounded-full mx-auto mb-6 flex items-center justify-center text-white font-black text-2xl italic uppercase shadow-xl shadow-indigo-100 border-4 border-white">FD</div>
                    <h4 class="text-xl font-black text-gray-900 uppercase italic leading-none">Febriansah Dirgantara</h4>
                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mt-3">Project Manager & Lead Programmer</p>
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
                        <h4 class="text-lg font-black text-gray-900 uppercase italic leading-none">Fajri Lukman</h4>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">Team Member</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="kontak" class="bg-white py-32">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="bg-indigo-600 rounded-[3rem] p-16 shadow-2xl shadow-indigo-200 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
                <h3 class="text-3xl font-black text-white uppercase italic tracking-tighter mb-6 relative z-10 text-center">Siap Mengoptimalkan <br> Kualitas Produksi?</h3>
                <p class="text-indigo-100 font-bold mb-10 relative z-10 opacity-80 uppercase text-xs tracking-widest text-center">Hubungi langsung Team Lead kami melalui LinkedIn.</p>
                
                <a href="https://www.linkedin.com/in/febrid" target="_blank" class="inline-flex items-center gap-3 bg-white text-indigo-600 px-10 py-5 rounded-[1.5rem] text-xs font-black uppercase tracking-widest hover:bg-gray-100 transition-all shadow-xl">
                    <i data-lucide="linkedin" class="w-4 h-4"></i> Hubungi Febriansah Dirgantara
                </a>
            </div>
        </div>
    </section>

    <footer class="py-12 text-center">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.4em]">
            &copy; {{ date('Y') }} <span class="text-indigo-600 italic">Qual Nova QC System</span> — Developed by Quetzal Team
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