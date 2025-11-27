<x-app-layout>


    <div class="py-10 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">
      
     <!-- 
        <div class="mb-4 flex items-center space-x-2">
            <form method="GET" action="{{ route('laporan.download') }}">
                <label for="month" class="mr-2 font-medium"> Periode:</label>
                <select id="month" name="month" class="border rounded px-2 py-1">
                    @foreach($bulanTersedia as $bulan)
                        <option value="{{ $bulan }}" {{ request('month') == $bulan ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($bulan.'-01')->format('F Y') }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded">
                    Export PDF
                </button>
            </form>
        </div>
    --

          <!-- Statistik Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @php
                            $cards = [
                            ['title'=>'Total Data Cacat','value'=>$totalCacat,'color'=>'from-blue-600 to-indigo-700 text-white'],
                            ['title'=>'Total Jenis Cacat','value'=>$totalJenis,'color'=>'from-teal-500 to-green-600 text-white'],
                            ['title'=>'Total User','value'=>$totalUser,'color'=>'from-gray-500 to-gray-700 text-white'],
                            ['title'=>'Verifikasi Valid','value'=>$verifikasiValid,'color'=>'from-blue-400 to-blue-600 text-white'],
                            ['title'=>'Verifikasi Belum Valid','value'=>$verifikasiBelum,'color'=>'from-red-500 to-red-700 text-white'],
                            ['title'=>'Status Sistem','value'=>$statusSistem,'color'=>'from-amber-500 to-yellow-600 text-gray-900'],
                            ['title'=>'Cacat Terbanyak','value'=>$jenisTerbanyak,'color'=>'from-purple-600 to-indigo-800 text-white'],
                            ['title'=>'Mesin Bermasalah Terbaru','value'=>$mesinBermasalah,'color'=>'from-gray-700 to-gray-900 text-white']
                        ];

                    @endphp

                    @foreach ($cards as $card)
                        <div class="bg-gradient-to-r {{ $card['color'] }} rounded-2xl text-white shadow-lg p-5 transform hover:scale-[1.03] transition">
                            <p class="text-sm uppercase tracking-wide opacity-80">{{ $card['title'] }}</p>
                            <p class="text-1xl font-semibold mt-1">{{ $card['value'] }}</p>
                        </div>
                    @endforeach

                </div>


      

            <!-- Chart Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-6">
                <!-- Grafik Tren Produksi -->
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                    <div class="flex items-center gap-4 mb-4">
                            <select id="rangeFilter" class="border border-gray-300 rounded-lg p-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <option value="day" selected>Per Hari</option>
                                <option value="week">Per Minggu</option>
                                <option value="month">Per Bulan</option>
                                <option value="year">Per Tahun</option>
                            </select>

                            <button id="filterBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow transition duration-200">
                                Terapkan
                            </button>
                     </div>

                        <h4 class="text-lg font-semibold text-gray-800 mb-4">📈 Tren Data Cacat</h4>
                        <canvas id="trendChart" height="150"></canvas>
                    </div>


                    <!-- Grafik Distribusi Jenis Cacat -->
                    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">📊 Distribusi Sumber Cacat</h4>
                        <canvas id="jenisChart" height="150"></canvas>
                    </div>
              </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-6">
                <!-- Grafik Status Verifikasi -->
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100" style="max-height: 400px; overflow-y: auto;">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">🧾 Status Verifikasi</h4>
                    <canvas id="verifikasiChart" height="150"></canvas>
                </div>

                
           
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100" style="max-height: 400px; overflow-y: auto;">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">📌 Status Data Cacat</h4>
                <canvas id="statusCacatChart"></canvas>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 lg:col-span-2" style="max-height: 400px; overflow-y: auto;">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">⚙️ Top Mesin Rusak</h4>
                    <canvas id="mesinChart"></canvas>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 lg:col-span-2" style="max-height: 400px; overflow-y: auto;">
    <h4 class="text-lg font-semibold text-gray-800 mb-4">🤖 Prediksi Kecacatan (Regresi Logistik)</h4>

    @if($prediksiRegresi->isEmpty())
        <p class="text-gray-500">Belum ada data cukup untuk menghasilkan prediksi.</p>
    @else
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b">
                <th class="py-2">Jenis Cacat</th>
                <th class="py-2">Mesin</th>
                <th class="py-2">Probabilitas Terverifikasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prediksiRegresi as $p)
            <tr class="border-b">
                <td class="py-1">{{ $p['jenis_cacat'] }}</td>
                <td class="py-1">{{ $p['mesin'] }}</td>
                <td class="py-1 font-semibold text-blue-600">{{ $p['probabilitas'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

          
        </div>
          <!-- Footer -->
          <div class="text-center text-gray-400 text-sm pt-10">
                © {{ date('Y') }} <span class="font-semibold text-indigo-600">Qual Nova QC System</span> — Developed by <span class="font-medium">Quetzal Team's</span>
            </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
   

        const ctx = document.getElementById('trendChart').getContext('2d');
            let trendChart;

            async function loadTrendChart(range = 'day') {
                const res = await fetch(`/dashboard/chart-trend?range=${range}`);
                const json = await res.json();

                if (trendChart) trendChart.destroy();

                trendChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: json.labels,
                        datasets: [{
                            label: 'Jumlah Cacat',
                            data: json.totals,
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
                            x: { title: { display: true, text: 'Periode' }}
                        }
                    }
                });
            }

            loadTrendChart();
            document.getElementById('filterBtn').addEventListener('click', () => {
                const range = document.getElementById('rangeFilter').value;
                loadTrendChart(range);
            });

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
                indexAxis: 'x', 
                responsive: true,
                plugins: { legend: { display: false }},
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Jumlah Cacat' }},
                    x: { title: { display: true, text: 'Jenis Cacat' }}
                }
            }
        });

const ctxVerifikasi = document.getElementById('verifikasiChart').getContext('2d');

        new Chart(ctxVerifikasi, {
            type: 'bar', 
            data: {
                labels: ['Valid', 'Belum Valid'],
                datasets: [{
                    label: 'Jumlah Verifikasi',
                    data: [{{ $verifikasiValid }}, {{ $verifikasiBelum }}],
                    backgroundColor: ['#22C55E', '#f5d742'],
                    borderColor: ['#16A34A', '#D4B500'],
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y', 
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Data'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Status Verifikasi'
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' data';
                            }
                        }
                    }
                }
            }
        });

        const ctxMesin = document.getElementById('mesinChart').getContext('2d');

            
        new Chart(ctxMesin, {
            type: 'bar',
            data: {
                labels: @json($namaMesin),
                datasets: [{
                    label: 'Jumlah Cacat Valid',
                    data: @json($jumlahCacatMesin),
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                indexAxis: 'y', 
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Cacat Valid'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Mesin'
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.x + ' cacat';
                            }
                        }
                    }
                }
            }
        });


        const ctxStatusCacat = document.getElementById('statusCacatChart').getContext('2d');

new Chart(ctxStatusCacat, {
    type: 'bar',
    data: {
        labels: @json($statusLabels),
        datasets: [{
            label: 'Jumlah',
            data: @json($statusTotals),
            backgroundColor: ['#F59E0B', '#22C55E','#3B82F6', '#EF4444'],
            borderColor: ['#16A34A', '#D97706', '#B91C1C', '#1D4ED8'],
            borderWidth: 1,
            borderRadius: 5
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Jumlah Data' }
            },
            x: {
                title: { display: true, text: 'Status' }
            }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
        function updateStatusSistem() {
            fetch('/dashboard/status-sistem')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('statusSistem').innerText = data.status;
                });
        }

        setInterval(updateStatusSistem, 500);
    </script>
</x-app-layout>
