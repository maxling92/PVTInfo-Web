@extends('layouts.DashboardHasil')

@section('container')
<div class="container">
    <h1>{{ $title }}</h1>
    
    <h2>Data Pengukuran: {{ $datapengukuran->namadata }}</h2>
    
    <!-- Graph -->
    <canvas id="chart-container" style="width: 100%; height: 400px;"></canvas>
    
    <!-- Table -->
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Waktu Milidetik</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datahasils as $datahasil)
                <tr>
                    <td>{{ $datahasil->nomor }}</td>
                    <td>{{ $datahasil->waktu_milidetik }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('chart-container').getContext('2d');

        var datahasils = @json($datahasils);
        var labels = datahasils.map(function(datahasil) {
            return datahasil.nomor;
        });
        var data = datahasils.map(function(datahasil) {
            return datahasil.waktu_milidetik;
        });
        var backgroundColors = data.map(function(waktu_milidetik) {
            if (waktu_milidetik < 200) {
                return 'rgba(54, 162, 235, 0.2)'; // blue
            } else if (waktu_milidetik < 400) {
                return 'rgba(75, 192, 192, 0.2)'; // green
            } else if (waktu_milidetik < 600) {
                return 'rgba(255, 206, 86, 0.2)'; // yellow
            } else {
                return 'rgba(255, 99, 132, 0.2)'; // red
            }
        });
        var borderColors = data.map(function(waktu_milidetik) {
            if (waktu_milidetik < 200) {
                return 'rgba(54, 162, 235, 1)'; // blue
            } else if (waktu_milidetik < 400) {
                return 'rgba(75, 192, 192, 1)'; // green
            } else if (waktu_milidetik < 600) {
                return 'rgba(255, 206, 86, 1)'; // yellow
            } else {
                return 'rgba(255, 99, 132, 1)'; // red
            }
        });

        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Waktu Milidetik',
                    data: data,
                    borderColor: borderColors,
                    backgroundColor: backgroundColors,
                    pointBackgroundColor: backgroundColors,
                    pointBorderColor: borderColors
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Nomor'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Waktu Milidetik'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
