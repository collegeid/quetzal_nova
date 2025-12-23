<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Qual Nova | Quetzal Team') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <script src="https://cdn.tailwindcss.com"></script>

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
            /* Reset Font ke Plus Jakarta Sans */
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: #f8fafc;
                color: #1e293b;
            }

            /* Efek Glassmorphism Global */
            .glass {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            /* Shadow Premium dari Jenis Cacat */
            .shadow-premium {
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            }

            /* Utility untuk sudut membulat lebar */
            .rounded-custom {
                border-radius: 2rem;
            }

            /* Mencegah Alpine "berkedip" saat reload */
            [x-cloak] { display: none !important; }

            /* Scrollbar Styling agar lebih clean */
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: #f1f1f1; }
            ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased selection:bg-indigo-100 selection:text-indigo-700">
        <div class="min-h-screen bg-[#f8fafc]">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-40">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight italic uppercase">
                            {{ $header }}
                        </h1>
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if ($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: '<span class='text-xl font-black uppercase italic text-rose-600'>Terjadi Kesalahan!</span>',
                            html: '<ul class='text-left text-sm font-bold text-gray-600 list-disc list-inside'>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                            customClass: { popup: 'rounded-[2rem] shadow-2xl p-8' },
                            confirmButtonColor: '#f43f5e',
                            confirmButtonText: 'PERBAIKI'
                        });
                    });
                </script>
            @endif

        <script>
            // === CONFIG SWEETALERT GLOBAL (Tema Bold/Modern) ===
            const swalCustom = {
                customClass: {
                    popup: 'rounded-[2rem] border-none shadow-2xl p-8',
                    confirmButton: 'rounded-xl font-black px-8 py-3 tracking-widest text-xs uppercase transition-all hover:scale-105 mx-2',
                    cancelButton: 'rounded-xl font-black px-8 py-3 tracking-widest text-xs uppercase transition-all hover:scale-105 mx-2'
                },
                buttonsStyling: true
            };

            document.addEventListener("DOMContentLoaded", () => {
                // Mode dark optional (jika kamu pakai dark mode tailwind)
                if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }

                // Handler form Create secara global
                const createForm = document.getElementById("createForm");
                if (createForm) {
                    createForm.addEventListener("submit", function(e) {
                        e.preventDefault();
                        Swal.fire({
                            ...swalCustom,
                            title: '<span class="text-xl font-black italic uppercase">Simpan Data?</span>',
                            text: "Pastikan data yang diinput sudah valid.",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonColor: "#4f46e5",
                            cancelButtonColor: "#94a3b8",
                            confirmButtonText: "YA, SIMPAN",
                            cancelButtonText: "BATAL"
                        }).then((result) => {
                            if (result.isConfirmed) createForm.submit();
                        });
                    });
                }
            });

            // Fungsi Delete Global yang dipakai di Jenis Cacat & Halaman lain
            function confirmDelete(id, formId = null) {
                const form = formId ? document.getElementById(formId) : document.getElementById('deleteForm-' + id);
                
                Swal.fire({
                    ...swalCustom,
                    title: '<span class="text-xl font-black italic uppercase text-rose-600">Hapus Data?</span>',
                    html: '<p class="text-sm text-gray-500 font-bold uppercase tracking-tight">Data ini akan dihilangkan permanen dari database.</p>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f43f5e',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'YA, HAPUS!',
                    cancelButtonText: 'BATAL',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            }

            // Notifikasi Sukses Otomatis dari Session Laravel
            @if(session('success'))
                Swal.fire({
                    ...swalCustom,
                    icon: 'success',
                    title: '<span class="text-xl font-black italic uppercase text-emerald-600">Berhasil!</span>',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#10b981',
                    timer: 3000
                });
            @endif
        </script>
    </body>
</html>