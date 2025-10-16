<x-app-layout>


    <div class="py-10 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $cards = [
                        ['title'=>'Total Data Cacat','value'=>$totalCacat,'color'=>'from-blue-500 to-blue-600'],
                        ['title'=>'Total Jenis Cacat','value'=>$totalJenis,'color'=>'from-green-500 to-emerald-600'],
                        ['title'=>'Total User','value'=>$totalUser,'color'=>'from-indigo-500 to-purple-600'],
                        ['title'=>'Verifikasi Valid','value'=>$verifikasiValid,'color'=>'from-yellow-400 to-amber-500 text-gray-900']
                    ];
                @endphp
                @foreach ($cards as $card)
                    <div class="bg-gradient-to-r {{ $card['color'] }} rounded-2xl text-white shadow-lg p-5 transform hover:scale-[1.03] transition">
                        <p class="text-sm uppercase tracking-wide opacity-80">{{ $card['title'] }}</p>
                        <h3 class="text-4xl font-bold mt-1">{{ $card['value'] }}</h3>
                    </div>
                @endforeach
            </div>

            <!-- Status & Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-red-500 rounded-2xl p-6 shadow-md text-white hover:shadow-lg transition">
                    <h4 class="text-lg font-semibold">Verifikasi Belum Valid</h4>
                    <p class="text-3xl font-bold mt-2">{{ $verifikasiBelum }}</p>
                </div>
                <div class="bg-gradient-to-r from-teal-500 to-cyan-600 rounded-2xl p-6 shadow-md text-white hover:shadow-lg transition">
                    <h4 class="text-lg font-semibold">Status Sistem</h4>
                    <p class="text-xl mt-1">✅ Semua proses berjalan normal</p>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-6">
                <!-- Grafik Tren Produksi -->
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">📈 Tren Data Cacat per Hari</h4>
                    <canvas id="trendChart" height="150"></canvas>
                </div>

                <!-- Grafik Distribusi Jenis Cacat -->
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">📊 Distribusi Jenis Cacat</h4>
                    <canvas id="jenisChart" height="150"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-6">
                <!-- Grafik Status Verifikasi -->
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">🧾 Status Verifikasi</h4>
                    <canvas id="verifikasiChart" height="150"></canvas>
                </div>

                <!-- Grafik Performa Mesin -->
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">⚙️ Performa Mesin</h4>
                    <canvas id="mesinChart" height="150"></canvas>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                    <h5 class="text-gray-600 text-sm uppercase tracking-wide mb-2">Jenis Cacat Terbanyak</h5>
                    <p class="text-2xl font-bold text-gray-800">{{ $jenisTerbanyak }}</p>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                    <h5 class="text-gray-600 text-sm uppercase tracking-wide mb-2">Mesin Bermasalah</h5>
                    <p class="text-2xl font-bold text-gray-800">{{ $mesinBermasalah }}</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-gray-400 text-sm pt-10">
                © {{ date('Y') }} <span class="font-semibold text-indigo-600">Qual Nova QC System</span> — Developed by <span class="font-medium">Quetzal Team's</span>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        // 🔹 Chart 1: Tren Data Cacat per Hari
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($tanggalCacat) !!},
                datasets: [{
                    label: 'Jumlah Cacat',
                    data: {!! json_encode($jumlahCacatPerHari) !!},
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59,130,246,0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#1D4ED8'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false }},
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Jumlah Cacat' }},
                    x: { title: { display: true, text: 'Tanggal' }}
                }
            }
        });

        // 🔹 Chart 2: Distribusi Jenis Cacat
        new Chart(document.getElementById('jenisChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($namaJenisCacat) !!},
                datasets: [{
                    label: 'Jumlah',
                    data: {!! json_encode($jumlahPerJenis) !!},
                    backgroundColor: ['#6366F1','#10B981','#F59E0B','#EF4444','#3B82F6','#8B5CF6']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false }},
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Jumlah Cacat' }},
                    x: { title: { display: true, text: 'Jenis Cacat' }}
                }
            }
        });

        // 🔹 Chart 3: Status Verifikasi
        new Chart(document.getElementById('verifikasiChart'), {
            type: 'doughnut',
            data: {
                labels: ['Valid', 'Belum Valid'],
                datasets: [{
                    data: [{{ $verifikasiValid }}, {{ $verifikasiBelum }}],
                    backgroundColor: ['#22C55E', '#EF4444'],
                    hoverOffset: 8
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom' }
                },
                cutout: '65%'
            }
        });

        // 🔹 Chart 4: Performa Mesin (Radar)
        new Chart(document.getElementById('mesinChart'), {
            type: 'radar',
            data: {
                labels: {!! json_encode($namaMesin) !!},
                datasets: [{
                    label: 'Tingkat Kinerja (%)',
                    data: {!! json_encode($kinerjaMesin) !!},
                    backgroundColor: 'rgba(16,185,129,0.2)',
                    borderColor: '#10B981',
                    borderWidth: 2,
                    pointBackgroundColor: '#059669'
                }]
            },
            options: {
                elements: { line: { tension: 0.3 }},
                scales: { r: { beginAtZero: true, max: 100 }},
                plugins: { legend: { display: false }}
            }
        });
    </script>
</x-app-layout>
