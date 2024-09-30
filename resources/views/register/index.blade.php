@extends('layouts.Main')

@section('container')
<div class="row justify-content-center">
    <div class="col-lg-4">
        <main class="form-registration">
            <form action="/register" method="post">
                @csrf
            <img class="mb-4" src="img/ic_launcher.png" alt="" width="65" height="60">
            <h1 class="h3 mb-3 fw-normal">Silahkan daftar akun</h1>
        
            <div class="form-floating">
                <input type="text" name="name" class="form-control @error("name") is-invalid @enderror" 
                 id="name" placeholder="Name" required value="{{ old('name') }}">
                <label for="name">Nama</label>
                @error("name")
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>   
                @enderror
            </div>
            <div class="form-floating">
                <input type="email" name="email" class="form-control @error("email") is-invalid @enderror" 
                 id="email" placeholder="name@example.com" required value="{{ old('email') }}">
                <label for="email">Alamat Email</label>
                @error("email")
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>   
                @enderror
            </div>
            <div class="form-floating">
                <input type="password" name="password" class="form-control @error("password") is-invalid @enderror" 
                 id="password" placeholder="Password" required>
                <label for="password">Password</label>
                @error("password")
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>   
                @enderror
            </div>
            <div class="form-floating mt-3">
                <input type="text" name="nama_perusahaan" class="form-control @error('nama_perusahaan') is-invalid @enderror" 
                    id="nama_perusahaan" placeholder="Nama Perusahaan" required value="{{ old('nama_perusahaan') }}">
                <label for="nama_perusahaan">Nama Perusahaan</label>
                @error('nama_perusahaan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>   
                @enderror
            </div>
            <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">Daftar</button>
            </form>
            <small class="mt-3"><a href="/login">Sudah daftar ?</a></small>
        </main>
    </div>
</div>
    
@endsection