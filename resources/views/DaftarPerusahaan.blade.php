<!-- resources/views/DaftarPerusahaan.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Perusahaan</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <li class="nav-item">
        <a class="nav-link" href="/">Home</a>
    </li>
</head>
<body>
    <div class="container">
        <h1>Daftar Perusahaan</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#TambahPerusahaan">Tambah Perusahaan</button>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Perusahaan</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>Telepon</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($perusahaans as $perusahaan)
                    <tr>
                        <td>{{ $perusahaan->nama_perusahaan }}</td>
                        <td>{{ $perusahaan->alamat }}</td>
                        <td>{{ $perusahaan->email_resmi }}</td>
                        <td>{{ $perusahaan->telepon }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('TambahPerusahaan')

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
