<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quality Control Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- 🌐 Navbar -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center py-4 px-6">
            <h1 class="text-xl font-bold text-blue-600 flex items-center gap-2">
                <i data-lucide="activity" class="w-6 h-6"></i>Qual Nova
            </h1>

            <nav class="space-x-6 hidden md:flex">
                <a href="#fitur" class="text-gray-700 hover:text-blue-600 font-medium">Fitur</a>
                <a href="#tentang" class="text-gray-700 hover:text-blue-600 font-medium">Tentang</a>
                <a href="#kontak" class="text-gray-700 hover:text-blue-600 font-medium">Kontak</a>
            </nav>

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
    </header>

    <!-- 🏠 Hero Section -->
    <section class="max-w-7xl mx-auto text-center py-20 px-6">
        <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
            Sistem Quality Control <span class="text-blue-600">Cacat Kain</span>
        </h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-10">
            Platform modern untuk memantau, mencatat, dan menganalisis jenis cacat pada kain 
            secara real-time. Didesain untuk efisiensi dan akurasi dalam proses produksi tekstil.
        </p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Mulai Sekarang
            </a>
            <a href="#fitur" class="border border-blue-600 text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 transition">
                Lihat Fitur
            </a>
        </div>
    </section>

    <!-- 💡 Fitur Section -->
    <section id="fitur" class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-6">
            <h3 class="text-3xl font-bold text-center text-gray-800 mb-12">Fitur Utama</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="bg-blue-50 p-8 rounded-2xl shadow hover:shadow-lg transition">
                    <i data-lucide="clipboard-check" class="w-10 h-10 text-blue-600 mb-4"></i>
                    <h4 class="text-xl font-semibold mb-2">Verifikasi Data</h4>
                    <p class="text-gray-600">Menampilkan hasil verifikasi cacat secara cepat dan transparan untuk setiap produksi.</p>
                </div>
                <div class="bg-blue-50 p-8 rounded-2xl shadow hover:shadow-lg transition">
                    <i data-lucide="bar-chart-2" class="w-10 h-10 text-blue-600 mb-4"></i>
                    <h4 class="text-xl font-semibold mb-2">Analisis Statistik</h4>
                    <p class="text-gray-600">Grafik tren cacat, performa mesin, dan distribusi jenis cacat untuk pengambilan keputusan.</p>
                </div>
                <div class="bg-blue-50 p-8 rounded-2xl shadow hover:shadow-lg transition">
                    <i data-lucide="users" class="w-10 h-10 text-blue-600 mb-4"></i>
                    <h4 class="text-xl font-semibold mb-2">Manajemen Pengguna</h4>
                    <p class="text-gray-600">Kelola akun operator, admin, dan supervisor dengan kontrol akses yang aman.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 🧵 Tentang Section -->
    <section id="tentang" class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto text-center px-6">
            <h3 class="text-3xl font-bold mb-6">Tentang Aplikasi</h3>
            <p class="text-gray-600 text-lg max-w-3xl mx-auto leading-relaxed">
                Aplikasi ini dikembangkan untuk mendukung proses <b>Quality Control</b> pada industri tekstil. 
                Dengan integrasi data cacat kain dan laporan otomatis, sistem membantu tim produksi 
                meminimalkan kesalahan dan meningkatkan efisiensi kerja.
            </p>
        </div>
    </section>

    <!-- 📞 Kontak Section -->
    <section id="kontak" class="bg-white py-20">
        <div class="max-w-6xl mx-auto text-center px-6">
            <h3 class="text-3xl font-bold mb-6">Hubungi Kami</h3>
            <p class="text-gray-600 mb-8">Ada pertanyaan atau ingin demo sistem?</p>
            <a href="mailto:support@qualnova.id" 
               class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                <i data-lucide="mail"></i> Kirim Email
            </a>
        </div>
    </section>

    <!-- ⚙️ Footer -->
    <footer class="bg-gray-900 text-gray-400 py-8 text-center">
        <p class="text-sm">&copy; {{ date('Y') }} Qual Nova • Dikembangkan oleh <span class="text-blue-400">Quetzal Team's</span></p>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
