<x-app-layout>
    <div class="py-10 bg-[#f8fafc] min-h-screen">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 space-y-8">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white shadow-premium p-8 rounded-custom border border-white/60">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight italic uppercase leading-none">
                        Dashboard <span class="text-indigo-600">Analytics</span>
                    </h2>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                        <span class="flex h-2 w-2 rounded-full bg-indigo-500 animate-ping"></span>
                        Real-time Monitoring Qual Nova
                    </p>
                </div>

                <form method="GET" action="{{ route('laporan.download') }}" class="flex items-center gap-3 bg-gray-50 p-2 rounded-2xl border border-gray-100 shadow-inner">
                    <div class="pl-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <select id="month" name="month" class="bg-transparent border-none focus:ring-0 text-xs font-black uppercase tracking-widest text-gray-600 cursor-pointer">
                        @foreach($bulanTersedia as $bulan)
                            <option value="{{ $bulan }}" {{ request('month') == $bulan ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($bulan.'-01')->format('F Y') }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-100 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" /></svg>
                        Export PDF
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $cards = [
                        ['title'=>'Total Data Cacat','value'=>$totalCacat,'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','color'=>'indigo'],
                        ['title'=>'Jenis Cacat','value'=>$totalJenis,'icon'=>'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10','color'=>'teal'],
                        ['title'=>'Total User','value'=>$totalUser,'icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z','color'=>'blue'],
                        ['title'=>'Verifikasi Valid','value'=>$verifikasiValid,'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'emerald'],
                        ['title'=>'Verifikasi Pending','value'=>$verifikasiBelum,'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'rose'],
                        ['title'=>'Status Sistem','value'=>$statusSistem,'icon'=>'M13 10V3L4 14h7v7l9-11h-7z','color'=>'amber','id'=>'statusSistem'],
                        ['title'=>'Top Issues','value'=>$jenisTerbanyak,'icon'=>'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z','color'=>'purple'],
                        ['title'=>'Latest Machine','value'=>$mesinBermasalah,'icon'=>'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z','color'=>'slate'],
                    ];
                @endphp

                @foreach ($cards as $card)
                    <div class="bg-white p-6 rounded-custom shadow-premium border border-white hover:-translate-y-1 transition-all duration-300 group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 rounded-2xl bg-{{ $card['color'] }}-50 text-{{ $card['color'] }}-600 group-hover:bg-{{ $card['color'] }}-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}" /></svg>
                            </div>
                            <span class="flex h-2 w-2 rounded-full bg-{{ $card['color'] }}-400 opacity-50"></span>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-1">{{ $card['title'] }}</p>
                        <p id="{{ $card['id'] ?? '' }}" class="text-xl font-black text-gray-900 uppercase italic truncate">
                            {{ $card['value'] }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-custom p-8 shadow-premium border border-white overflow-hidden">
                    <div class="flex items-center justify-between mb-8">
                        <h4 class="text-sm font-black text-gray-900 uppercase italic tracking-widest flex items-center gap-2">
                            <span class="w-2 h-6 bg-indigo-600 rounded-full"></span> 📈 Tren Produksi
                        </h4>
                        <div class="flex items-center gap-2 bg-gray-50 p-1.5 rounded-xl border border-gray-100">
                            <select id="rangeFilter" class="bg-transparent border-none text-[10px] font-black uppercase tracking-widest text-gray-500 focus:ring-0 cursor-pointer">
                                <option value="day" selected>Harian</option>
                                <option value="week">Mingguan</option>
                                <option value="month">Bulanan</option>
                            </select>
                            <button id="filterBtn" class="bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </button>
                        </div>
                    </div>
                    <div class="h-[300px]"> <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-custom p-8 shadow-premium border border-white">
                    <h4 class="text-sm font-black text-gray-900 uppercase italic tracking-widest flex items-center gap-2 mb-8">
                        <span class="w-2 h-6 bg-teal-500 rounded-full"></span> 📊 Sumber Kerusakan
                    </h4>
                    <div class="h-[300px]"> <canvas id="jenisChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-custom p-8 shadow-premium border border-white">
                    <h4 class="text-sm font-black text-gray-900 uppercase italic tracking-widest mb-8">🧾 Status Verifikasi</h4>
                    <div class="h-[250px]">
                        <canvas id="verifikasiChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-custom p-8 shadow-premium border border-white">
                    <h4 class="text-sm font-black text-gray-900 uppercase italic tracking-widest mb-8">📌 Status Data Cacat</h4>
                    <div class="h-[250px]">
                        <canvas id="statusCacatChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1 bg-white rounded-custom p-8 shadow-premium border border-white">
                    <h4 class="text-sm font-black text-gray-900 uppercase italic tracking-widest mb-8">⚙️ Top Unit Bermasalah</h4>
                    <div class="h-[300px]">
                        <canvas id="mesinChart"></canvas>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white rounded-custom overflow-hidden shadow-premium border border-white flex flex-col">
                    <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                        <h4 class="text-sm font-black text-gray-900 uppercase italic tracking-widest flex items-center gap-2">
                            <span class="w-2 h-6 bg-rose-500 rounded-full"></span> Analisis Prediksi Regresi
                        </h4>
                        <span class="text-[9px] font-bold text-rose-500 bg-rose-50 px-3 py-1 rounded-full uppercase">Regresi Logistik</span>
                    </div>
                    
                    <div class="p-0 max-h-[350px] overflow-y-auto"> 
                        @if($prediksiRegresi->isEmpty())
                            <div class="p-20 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">Belum ada data cukup untuk prediksi</div>
                        @else
                            <table class="w-full">
                                <thead class="sticky top-0 bg-white z-10">
                                    <tr class="bg-gray-50 text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                        <th class="px-8 py-4 text-left uppercase">Kategori Cacat</th>
                                        <th class="px-8 py-4 text-left uppercase">Unit Mesin</th>
                                        <th class="px-8 py-4 text-right uppercase">Probabilitas</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($prediksiRegresi as $p)
                                    <tr class="hover:bg-indigo-50/30 transition-colors">
                                        <td class="px-8 py-4 text-xs font-black text-gray-700 uppercase italic">{{ $p['jenis_cacat'] }}</td>
                                        <td class="px-8 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-widest">{{ $p['mesin'] }}</td>
                                        <td class="px-8 py-4 text-right">
                                            <div class="flex items-center justify-end gap-3">
                                                <div class="w-24 bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                                    <div class="bg-indigo-600 h-full rounded-full" style="width: {{ $p['probabilitas'] }}"></div>
                                                </div>
                                                <span class="text-xs font-black text-indigo-600">{{ $p['probabilitas'] }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>

            <div class="text-center pt-10 border-t border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.4em]">
                    © {{ date('Y') }} <span class="text-indigo-600 italic">Qual Nova QC System</span> — Created by Quetzal Team
                </p>
            </div>
        </div>
    </div>

    <script>
        // Global Config
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.font.weight = '700';
        Chart.defaults.color = '#94a3b8';

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
                        borderColor: '#6366f1',
                        backgroundColor: (context) => {
                            const chart = context.chart;
                            const {ctx, chartArea} = chart;
                            if (!chartArea) return null;
                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            gradient.addColorStop(0, 'rgba(99, 102, 241, 0)');
                            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.15)');
                            return gradient;
                        },
                        borderWidth: 4,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }},
                    scales: {
                        y: { beginAtZero: true, border: {display: false}, grid: {color: '#f1f5f9'}},
                        x: { border: {display: false}, grid: {display: false}}
                    }
                }
            });
        }

        loadTrendChart();
        document.getElementById('filterBtn').addEventListener('click', () => {
            loadTrendChart(document.getElementById('rangeFilter').value);
        });

        // Jenis Cacat Chart
        new Chart(document.getElementById('jenisChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($namaJenisCacat) !!},
                datasets: [{
                    data: {!! json_encode($jumlahPerJenis) !!},
                    backgroundColor: ['#6366F1','#10B981','#F59E0B','#EF4444','#3B82F6','#8B5CF6'],
                    borderRadius: 12,
                    barThickness: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }},
                scales: {
                    y: { beginAtZero: true, grid: {color: '#f1f5f9'}},
                    x: { grid: {display: false}}
                }
            }
        });

        // Verifikasi Chart
        new Chart(document.getElementById('verifikasiChart'), {
            type: 'bar', 
            data: {
                labels: ['Valid', 'Belum Valid'],
                datasets: [{
                    data: [{{ $verifikasiValid }}, {{ $verifikasiBelum }}],
                    backgroundColor: ['#10B981', '#F59E0B'],
                    borderRadius: 10
                }]
            },
            options: {
                indexAxis: 'y', 
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }},
                scales: {
                    x: { beginAtZero: true, grid: {color: '#f1f5f9'}},
                    y: { grid: {display: false}}
                }
            }
        });

        // Mesin Chart
        new Chart(document.getElementById('mesinChart'), {
            type: 'bar',
            data: {
                labels: @json($namaMesin),
                datasets: [{
                    data: @json($jumlahCacatMesin),
                    backgroundColor: '#f43f5e',
                    borderRadius: 8
                }]
            },
            options: {
                indexAxis: 'y', 
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }},
                scales: {
                    x: { beginAtZero: true, grid: {color: '#f1f5f9'}},
                    y: { grid: {display: false}}
                }
            }
        });

        // Status Cacat Chart
        new Chart(document.getElementById('statusCacatChart'), {
            type: 'bar',
            data: {
                labels: @json($statusLabels),
                datasets: [{
                    data: @json($statusTotals),
                    backgroundColor: ['#F59E0B', '#10B981','#3B82F6', '#EF4444'],
                    borderRadius: 8
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }},
                scales: {
                    x: { beginAtZero: true, grid: {color: '#f1f5f9'}},
                    y: { grid: {display: false}}
                }
            }
        });

        function updateStatusSistem() {
            fetch('/dashboard/status-sistem')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('statusSistem').innerText = data.status.toUpperCase();
                });
        }
        setInterval(updateStatusSistem, 1000);
    </script>
</x-app-layout>