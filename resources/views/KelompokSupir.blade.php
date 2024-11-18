@extends('layouts.DashboardSupir')

@section('container')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Analisa Data Kelompok</h1>
</div>

<div class="mb-3">
    <strong>Filter:</strong>
    <ul>
        @if ($filters['tgllahir'])
            <li>Tanggal Lahir: {{ $filters['tgllahir'] }}</li>
        @endif
    </ul>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col"><a href="{{ route('datapengirim.groupAnalysis', ['sort_by' => 'nama_observant', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Nama Observant</a></th>
                <th scope="col"><a href="{{ route('datapengirim.groupAnalysis', ['sort_by' => 'tgllahir', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Tanggal Lahir</a></th>
                <th scope="col">Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datapengirims as $pengirim)
                <tr>
                    <td>{{ $pengirim->nama_observant }}</td>
                    <td>{{ $pengirim->tgllahir }}</td>
                    <td>{{ $avg_pengirims->firstWhere('nama_observant', $pengirim->nama_observant)['avg_pengirim'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div>
    <h3>Fatigue Analysis</h3>
    <ul>
        <li>Safe: {{ $fatigueCategories['safe'] }}</li>
        <li>Mild: {{ $fatigueCategories['mild'] }}</li>
        <li>Moderate: {{ $fatigueCategories['moderate'] }}</li>
        <li>Heavy: {{ $fatigueCategories['heavy'] }}</li>
    </ul>

    <p>Most Drivers Fall Into: 
        @if($mostFrequentCategory == 'safe')
            Relatively safe
        @elseif($mostFrequentCategory == 'mild')
            Mild fatigue
        @elseif($mostFrequentCategory == 'moderate')
            Moderate fatigue
        @elseif($mostFrequentCategory == 'heavy')
            Heavy fatigue
        @endif
    </p>
</div>

<canvas id="groupAnalysisChart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('groupAnalysisChart').getContext('2d');
    
    var xValues = @json($xValues); // x-axis values (drivers' names or similar)
    var yValues = @json($yValues); // y-axis values (avg_pengirim)

    // Define the chart
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: xValues,
            datasets: [{
                label: 'Average Pengirim (Rata-rata)',
                data: yValues,
                backgroundColor: 'rgba(0, 0, 0, 0.1)', // Default color for bars, can change per bar
                borderColor: 'rgba(0, 0, 0, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 100
                    }
                }
            },
            plugins: {
                // Handle coloring regions based on y-axis values
                beforeDatasetsDraw: function(chart) {
                    var ctx = chart.chart.ctx;
                    var yScale = chart.scales.y;
                    var chartArea = chart.chartArea;

                    // Define color regions
                    var regions = [
                        { color: 'rgba(0, 0, 255, 0.2)', start: 0, end: 300 },  // Blue region (safe)
                        { color: 'rgba(0, 255, 0, 0.2)', start: 301, end: 450 }, // Green region (mild fatigue)
                        { color: 'rgba(255, 255, 0, 0.2)', start: 451, end: 600 }, // Yellow region (moderate fatigue)
                        { color: 'rgba(255, 0, 0, 0.2)', start: 601, end: yScale.max } // Red region (heavy fatigue)
                    ];

                    regions.forEach(function(region) {
                        var yStart = yScale.getPixelForValue(region.start);
                        var yEnd = yScale.getPixelForValue(region.end);

                        ctx.fillStyle = region.color;
                        ctx.fillRect(chartArea.left, yEnd, chartArea.right - chartArea.left, yStart - yEnd);
                    });
                }
            }
        }
    });
</script>
@endsection

