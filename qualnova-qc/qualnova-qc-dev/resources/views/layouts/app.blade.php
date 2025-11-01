<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Quel Nova | Quetzal Team`s ') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
<!-- Tailwind CSS (CDN) -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Font & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8fafc;
    }

    /* Card effect */
    .glass {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
    }

    .shadow-soft {
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }
</style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
    // Mode dark optional toggle (biar keren)
    if (localStorage.theme === 'dark') document.documentElement.classList.add('dark');
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Tambahkan CDN SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    // === CREATE ===
    const createForm = document.getElementById("createForm");
    if (createForm) {
        createForm.addEventListener("submit", function(e) {
            e.preventDefault(); // stop submit otomatis
            Swal.fire({
                title: "Simpan Data?",
                text: "Apakah data sudah sesuai?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Simpan!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    createForm.submit();
                }
            });
        });
    }

    // === EDIT ===
    // kalau form edit ada banyak (1 per baris modal misalnya)
    const editForms = document.querySelectorAll('form[id^="editForm"]');
    editForms.forEach((form) => {
        form.addEventListener("submit", function(e) {
            e.preventDefault(); // stop submit otomatis
            Swal.fire({
                title: "Update Data?",
                text: "Pastikan data sudah benar sebelum disimpan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Update!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

});
</script>


<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm-' + id).submit();
        }
    });
}
</script>
    </body>
</html>
