<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Klik Hape') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- 1. KARTU STATISTIK -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Total Leads -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-500 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="mb-1 text-sm font-medium text-gray-500">Total Prospek (Leads)</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_leads']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ready Stock -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="mb-1 text-sm font-medium text-gray-500">Tipe HP Ready</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['ready_products']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stok Habis -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="mb-1 text-sm font-medium text-gray-500">Tipe HP Kosong</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['empty_products']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- WA Terkirim -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </div>
                        <div>
                            <p class="mb-1 text-sm font-medium text-gray-500">WA Broadcast Terkirim</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['wa_sent']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. AREA GRAFIK -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Grafik Pertumbuhan Leads (Line Chart) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Pertumbuhan Prospek (30 Hari Terakhir)</h3>
                        <p class="text-sm text-gray-500 mb-6">Melihat seberapa banyak orang yang klik "Ingatkan Saya" setiap harinya.</p>
                        <div class="relative h-72 w-full">
                            <canvas id="leadsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Grafik HP Incaran (Bar Chart Vertikal / List) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd" />
                            </svg>
                            Sinyal Kulakan (Top 5)
                        </h3>
                        <p class="text-sm text-gray-500 mb-6">HP yang stoknya kosong tapi paling banyak ditunggu orang saat ini.</p>

                        @if(count($wantedLabels) > 0)
                            <div class="relative h-64 w-full">
                                <canvas id="wantedChart"></canvas>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center h-48 text-center bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm font-medium text-gray-600">Semua aman!</p>
                                <p class="text-xs text-gray-500 mt-1">Tidak ada antrean tertunda untuk HP yang kosong.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Grafik Garis (Pertumbuhan Leads)
            const leadsCtx = document.getElementById('leadsChart').getContext('2d');
            new Chart(leadsCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Prospek Baru',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#6366f1', // Indigo 500
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3, // Membuat garis melengkung
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#6366f1',
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Orang ngantri';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 } // Menghilangkan koma desimal
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // 2. Grafik Bar (HP Paling Diincar)
            @if(count($wantedLabels) > 0)
            const wantedCtx = document.getElementById('wantedChart').getContext('2d');
            new Chart(wantedCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($wantedLabels) !!},
                    datasets: [{
                        label: 'Jumlah Antrean',
                        data: {!! json_encode($wantedData) !!},
                        backgroundColor: '#ef4444', // Red 500
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y', // Membuat grafik bar menjadi horizontal menyamping
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        },
                        y: {
                            grid: { display: false },
                            ticks: {
                                callback: function(value) {
                                    // Memotong label jika terlalu panjang
                                    let label = this.getLabelForValue(value);
                                    return label.length > 15 ? label.substr(0, 15) + '...' : label;
                                }
                            }
                        }
                    }
                }
            });
            @endif

        });
    </script>
</x-app-layout>
