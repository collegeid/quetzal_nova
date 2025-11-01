<x-app-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-8">
        <div class="bg-white shadow-lg rounded-3xl p-8 max-w-lg text-center border border-gray-100">
            <h2 class="text-2xl font-bold text-indigo-600 mb-3">👋 Selamat Datang, {{ $user->name }}!</h2>
            <p class="text-gray-600 mb-6">
                Anda login sebagai <span class="font-semibold text-gray-800">Operator Produksi</span>.
            </p>

            <h3 class="text-lg font-semibold text-gray-700 mb-2">Fitur yang dapat Anda akses:</h3>
            <ul class="text-left text-gray-600 list-disc list-inside space-y-1">
                <li>✅ Input Data Cacat</li>
                <li>✅ Lihat Daftar Produksi</li>
                <li>⚙️ Verifikasi akan dilakukan oleh QC</li>
                <li>🚫 Tidak memiliki akses ke laporan statistik</li>
            </ul>
        </div>
    </div>
</x-app-layout>
