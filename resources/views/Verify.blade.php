<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Verifikasi Kode</h2>
        <form action="{{ route('verify') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="code">Masukkan Kode Verifikasi</label>
                <input type="text" class="form-control" id="code" name="code" required>
                @if ($errors->has('code'))
                    <div class="alert alert-danger">{{ $errors->first('code') }}</div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Verifikasi</button>
        </form>
    </div>
</body>
