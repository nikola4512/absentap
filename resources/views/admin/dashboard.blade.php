@extends('admin.layouts.main')

@section('importheadAppend')
    <style>
        .content-wrapper {
            padding: 20px;
        }

        .chart-container.small {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 280px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chart-container.large {
            background-color: #ffffff;
            max-height: 250px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .chart-container.medium {
            max-height: 250px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 15px;
        }

        .containerdonutIn {
            max-width: 300px;
            height: 170px;
            align-items: center;
            display: flex;
            justify-content: center;
        }

        #lineChart {
            width: 100%;
            height: 200px;
            padding: 10px;
        }

        #donutChart {
            width: 100%;
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .row.dashboard-grid {
            display: flex;
            flex-wrap: wrap;
        }

        .col-md-3,
        .col-md-6,
        .col-md-9 {
            padding: 10px;
        }

        .col-md-3 {
            width: 25%;
        }

        .col-md-6 {
            width: 50%;
        }

        .col-md-9 {
            width: 75%;
        }

        .widget {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 260px;
            max-height: 245px;
            text-align: start;
        }

        .widget .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;

        }

        .widget .value {
            font-size: 24px;
            font-weight: bold;
            color: #006086;
        }

        /* Custom style for the legend */
        .chart-legend {
            height: 80px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 170px;
            margin-top: 20px;
            margin-left: 30px;
            width: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chart-legend ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: block;
            /* Mengubah display dari flex menjadi block */
        }

        .chart-legend ul li {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 10px;
            margin-bottom: 5px;
            /* Menambahkan jarak di antara elemen legenda */
        }

        .chart-legend ul li::before {
            content: '';
            display: inline-block;
            width: 12px;
            height: 12px;
            background-color: currentColor;
        }

        .no-data-message {
            text-align: center;
            color: #403939;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 30px;
        }

        .page-views-image {
            max-width: 100px;
            height: auto;
            float: left;
            text-align: right;
        }

        .page-views-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            text-align: right;
            margin-left: 140px;
        }

        .page-views-date-time {
            text-align: center;
            font-family: Arial, sans-serif;
            font-weight: bold;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            display: inline-block;

            width: 200px;
        }

        .page-views-date-time .day {
            font-size: 16px;
            margin: 0;
            color: #000;
        }

        .page-views-date-time .date {
            font-size: 12px;
            margin: 0;
            color: #000;
        }

        .page-views-date-time .time {
            font-size: 20px;
            margin: 0;
            color: #000;
            letter-spacing: 1px;

        }

        .title {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-weight: bold;
            padding-bottom: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12">
                <h3 class="content-header-title mb-0">Dashboard</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            @foreach ($breadcrumbs as $item)
                                @if (!$item['disabled'])
                                    <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
                                @else
                                    <li class="breadcrumb-item active">{{ $item['title'] }}</li>
                                @endif
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-6 col-12", style="text-align: right;">
                <div class="page-views-image">
                    <img src="{{ asset('images/' . $image) }}" alt="Page Views Image">
                </div>
                <div class="page-views-date-time">
                    <p class="day">{{ \Carbon\Carbon::now()->locale('en')->dayName }}</p>
                    <p class="date">{{ \Carbon\Carbon::now()->format('j F Y') }}</p>
                    <p class="time">{{ \Carbon\Carbon::now()->format('H : i : s') }}</p>
                </div>

            </div>
        </div>

        <div class="row dashboard-grid">
            <!-- Top Row Widgets -->
            <div class="col-md-3">
                <div class="widget">
                    <div class="title">Kehadiran Tertinggi</div>
                    <div class="value">
                        {{ $hadir_result ?? 'Data Tidak Tersedia' }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="widget">
                    <div class="title">Kehadiran Terendah</div>
                    <div class="value">
                        {{ $tidakhadir_result ?? 'Data Tidak Tersedia' }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container medium">
                    <canvas id="lineChart"></canvas>
                    <div class="title">Data Kehadiran Siswa</div>
                </div>
            </div>
        </div>

        <!-- Second Row -->
        <div class="row dashboard-grid">
            <div class="col-md-6">
                <div class="chart-container medium">

                </div>
            </div>
            <div class="col-md-3">
                <div class="chart-container small">
                    <div class="containerdonutIn">
                        @if ($hasDataToday)
                            <canvas id="donutChart"></canvas>
                        @else
                            <div class="no-data-message">
                                Belum ada data yang terinput di hari ini
                            </div>
                        @endif
                    </div>
                </div>

            </div>
            <div class="col-md-3">
                <div class="chart-legend">
                    <ul>
                        <li style="color: rgba(255, 99, 132, 1);">Izin Sakit</li>
                        <li style="color: rgba(54, 162, 235, 1);">Izin</li>
                        <li style="color: rgba(255, 206, 86, 1);">Tanpa Keterangan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('importfootAppend')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours} : ${minutes} : ${seconds}`;
            document.querySelector('.page-views-date-time .time').textContent = timeString;
        }
        updateTime();
        setInterval(updateTime, 1000);

        // Data line Chart
        const monthNames = @json($monthNames);
        const hadirCounts = @json($hadirCounts);
        const tidakHadirCounts = @json($tidakHadirCounts);

        const lineChartData = {
            labels: monthNames,
            datasets: [{
                    label: 'Hadir',
                    data: hadirCounts,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: false
                },
                {
                    label: 'Tidak Hadir',
                    data: tidakHadirCounts,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    fill: false
                }
            ]
        };

        // Data donut Chart
        var izin_sakit = {{ $izin_sakit }};
        var izin = {{ $izin }};
        var no_info = {{ $no_info }};

        const donutChartData = {
            labels: ['Izin Sakit', 'Izin', 'Tanpa Keterangan'],
            datasets: [{
                label: 'Jenis Ketidakhadiran',
                data: [izin_sakit, izin, no_info],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Grafik Garis
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        new Chart(lineCtx, {
            type: 'line',
            data: lineChartData,
            options: {
                plugins: {
                    title: {
                        display: false,
                        text: 'Data Kehadiran Siswa',
                        position: 'bottom'
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Grafik Donat
        const donutCtx = document.getElementById('donutChart').getContext('2d');
        new Chart(donutCtx, {
            type: 'doughnut',
            data: donutChartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false // Hide the default legend
                    }
                }
            }
        });
    </script>
@endsection
