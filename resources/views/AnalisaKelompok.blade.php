@extends('layouts.Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Analisa Data Kelompok</h1>
</div>

<div class="mb-3">
    <strong>Filter:</strong>
    <ul>
        @if ($filters['jenistest'])
            <li>Jenis Test: {{ $filters['jenistest'] }}</li>
        @endif
        @if ($filters['lokasi'])
            <li>Lokasi: {{ $filters['lokasi'] }}</li>
        @endif
        @if ($filters['tanggal'])
            <li>Tanggal: {{ $filters['tanggal'] }}</li>
        @endif
        @if ($filters['waktu'])
            <li>Waktu: {{ $filters['waktu'] }}</li>
        @endif
    </ul>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col"><a href="{{ route('datapengukuran.analyze', ['nama_observant' => $pengirim->nama_observant, 'sort_by' => 'nama_data', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Nama Data</a></th>
                <th scope="col"><a href="{{ route('datapengukuran.analyze', ['nama_observant' => $pengirim->nama_observant, 'sort_by' => 'tanggal', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Tanggal</a></th>
                <th scope="col"><a href="{{ route('datapengukuran.analyze', ['nama_observant' => $pengirim->nama_observant, 'sort_by' => 'lokasi', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Lokasi</a></th>
                <th scope="col"><a href="{{ route('datapengukuran.analyze', ['nama_observant' => $pengirim->nama_observant, 'sort_by' => 'jenistest', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Jenis Test</a></th>
                <th scope="col"><a href="{{ route('datapengukuran.analyze', ['nama_observant' => $pengirim->nama_observant, 'sort_by' => 'ratarata', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Rata-rata</a></th>
            </tr>
        </thead>
        <tbody>
            @foreach($datapengukurans as $datapengukuran)
                <tr>
                    <td>{{ $datapengukuran->nama_data }}</td>
                    <td>{{ $datapengukuran->tanggal }}</td>
                    <td>{{ $datapengukuran->lokasi }}</td>
                    <td>{{ $datapengukuran->jenistest }}</td>
                    <td>{{ $datapengukuran->ratarata }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div>
    <p>Rata-rata data yang ditampilkan : {{ $averageRatarata }}</p>
    <p>Analisis Trend sederhana: {{ $trend }}</p>
    <p>Analisis Kelelahan: {{ $fatigueMessage }}</p>
</div>

<canvas id="groupAnalysisChart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('groupAnalysisChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($xValues),
            datasets: [{
                label: 'Rata-rata',
                data: @json($yValues),
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                fill: false
            }]
        },
        options: {
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nama Data'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Rata-rata'
                    }
                }
            }
        }
    });
</script>
@endsection
