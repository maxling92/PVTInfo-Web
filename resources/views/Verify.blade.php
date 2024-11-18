@extends('layouts.Main')

@section('content')
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
@endsection
