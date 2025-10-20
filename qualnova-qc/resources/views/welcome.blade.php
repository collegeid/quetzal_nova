<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quality Control Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        // Tailwind dark mode config
        tailwind.config = {
            darkMode: 'class'
        }

        // Simpan preferensi di localStorage
        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateIcon(isDark);
        }

        function updateIcon(isDark) {
            const icon = document.getElementById('themeIcon');
            icon.setAttribute('data-lucide', isDark ? 'sun' : 'moon');
            lucide.createIcons();
        }

        // Terapkan preferensi sebelumnya
        document.addEventListener("DOMContentLoaded", function() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = saved === 'dark' || (!saved && prefersDark);
            if (isDark) document.documentElement.classList.add('dark');
            updateIcon(isDark);
        });
    </script>
</head>

<body class="bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-200 transition-colors duration-500">

    <!-- 🌐 Navbar -->
    <header class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50 transition-colors duration-500">
        <div class="max-w-7xl mx-auto flex justify-between items-center py-4 px-6">
            <h1 class="text-xl font-bold text-blue-600 dark:text-blue-400 flex items-center gap-2">
                <i data-lucide="activity" class="w-6 h-6"></i>Qual Nova
            </h1>

            <nav class="space-x-6 hidden md:flex">
                <a href="#fitur" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">Fitur</a>
                <a href="#tentang" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">Tentang</a>
                <a href="#kontak" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">Kontak</a>
            </nav>

            <div class="flex items-center gap-3">
                <button onclick="toggleDarkMode()" class="p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <i id="themeIcon" data-lucide="moon" class="w-5 h-5 text-gray-700 dark:text-gray-300"></i>
                </button>

                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </header>

<!-- 🏠 Hero Section -->
<section class="max-w-7xl mx-auto py-20 px-6 relative flex flex-col md:flex-row items-center justify-between transition-colors duration-500">
    <div class="md:w-1/2 text-center md:text-left mb-10 md:mb-0">
        <h2 class="text-4xl md:text-5xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            Sistem Quality Control <span class="text-blue-600 dark:text-blue-400">Cacat Kain</span>
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-xl mb-4">
            Platform modern untuk memantau, mencatat, dan menganalisis jenis cacat pada kain secara <b>real-time</b>.
        </p>
        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-xl mb-10">
            Dilengkapi dengan <b>dashboard interaktif</b>, <b>chart statistik</b>, dan <b>laporan otomatis</b> untuk semua level pengguna.
        </p>
        <div class="flex justify-center md:justify-start gap-4">
            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Mulai Sekarang
            </a>
            <a href="#fitur" class="border border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400 px-6 py-3 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-800 transition">
                Lihat Fitur
            </a>
        </div>
    </div>

    <div class="md:w-1/2 flex justify-center md:justify-end">
        <img src="{{ asset('storage/images/mockup-qualnova.png') }}" alt="Mockup Laptop & iPad" class="w-4/5 md:w-full rounded-xl shadow-xl">
    </div>
</section>

<!-- 💡 Fitur Section -->
<section id="fitur" class="bg-white dark:bg-gray-800 py-20 transition-colors duration-500">
    <div class="max-w-7xl mx-auto px-6">
        <h3 class="text-3xl font-bold text-center text-gray-800 dark:text-gray-100 mb-12">Fitur Utama</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="bg-blue-50 dark:bg-gray-700 p-8 rounded-2xl shadow hover:shadow-lg transition">
                <i data-lucide="clipboard-check" class="w-10 h-10 text-blue-600 dark:text-blue-400 mb-4"></i>
                <h4 class="text-xl font-semibold mb-2">Verifikasi Data</h4>
                <p class="text-gray-600 dark:text-gray-300">
                    Menampilkan hasil verifikasi cacat dengan transparan untuk setiap produksi.
                </p>
            </div>
            <div class="bg-blue-50 dark:bg-gray-700 p-8 rounded-2xl shadow hover:shadow-lg transition">
                <i data-lucide="bar-chart-2" class="w-10 h-10 text-blue-600 dark:text-blue-400 mb-4"></i>
                <h4 class="text-xl font-semibold mb-2">Analisis Statistik</h4>
                <p class="text-gray-600 dark:text-gray-300">
                    Visualisasi data cacat, tren, performa mesin, dan chart statistik lengkap.
                </p>
            </div>
            <div class="bg-blue-50 dark:bg-gray-700 p-8 rounded-2xl shadow hover:shadow-lg transition">
                <i data-lucide="users" class="w-10 h-10 text-blue-600 dark:text-blue-400 mb-4"></i>
                <h4 class="text-xl font-semibold mb-2">Manajemen Pengguna</h4>
                <p class="text-gray-600 dark:text-gray-300">
                    Kelola akun operator, admin, dan supervisor dengan kontrol akses aman.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- 🧵 Tentang Section -->
<section id="tentang" class="py-20 bg-gray-50 dark:bg-gray-900 transition-colors duration-500">
    <div class="max-w-6xl mx-auto text-center px-6">
        <h3 class="text-3xl font-bold mb-12 text-gray-800 dark:text-gray-100">Tim Pengembang</h3>
        <p class="text-gray-600 dark:text-gray-300 text-lg max-w-3xl mx-auto leading-relaxed mb-12">
            Aplikasi ini dikembangkan untuk mendukung proses <b>Quality Control</b> pada industri tekstil.
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-lg transition">
                <h4 class="text-xl font-semibold mb-1">Project Manager</h4>
                <p class="text-gray-600 dark:text-gray-300 mb-2">Febriansah Dirgantara</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-lg transition">
                <h4 class="text-xl font-semibold mb-1">Lead Programmer</h4>
                <p class="text-gray-600 dark:text-gray-300 mb-2">Febriansah Dirgantara</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-lg transition">
                <h4 class="text-xl font-semibold mb-1">Anggota Tim</h4>
                <ul class="text-gray-600 dark:text-gray-300 list-disc list-inside">
                    <li>Rizal Maulana</li>
                    <li>Rifqii Fauzi A</li>
                    <li>Fajri Lukman</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="kontak" class="bg-white dark:bg-gray-800 py-20 transition-colors duration-500">
    <div class="max-w-6xl mx-auto text-center px-6">
        <h3 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">Hubungi Team Lead Kami</h3>
        <p class="text-gray-600 dark:text-gray-300 mb-8">
            Ada pertanyaan atau ingin demo sistem? Hubungi langsung Team Lead kami melalui LinkedIn.
        </p>
        <div class="flex justify-center gap-4 flex-wrap">
            <a href="https://www.linkedin.com/in/febrid" target="_blank"
               class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                <i data-lucide="linkedin"></i> LinkedIn Febriansah Dirgantara
            </a>
        </div>
    </div>
</section>

<footer class="bg-gray-900 dark:bg-black text-gray-400 py-8 text-center transition-colors duration-500">
    <p class="text-sm">&copy; {{ date('Y') }} Qual Nova • Dikembangkan oleh <span class="text-blue-400">Quatzal Team's</span></p>
</footer>

<script>
    lucide.createIcons();
</script>
</body>
</html>
