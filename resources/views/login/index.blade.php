@extends('layouts.Main')

@section('container')
<div class="row justify-content-center">
    <div class="col-lg-4">

        @if (session()->has('sukses'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('sukses') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @if (session()->has('loginError'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('loginError') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <main class="form-signin">
            <img class="mb-4" src="img/ic_launcher.png" alt="" width="65" height="60">
            <h1 class="h3 mb-3 fw-normal">Silahkan login akun</h1>

            <form action="/login" method="post">
                @csrf
                <div class="form-floating">
                    <input type="email" name="email" class="form-control @error("email") is-invalid @enderror" 
                     id="email" placeholder="name@example.com" autofocus required value="{{ old('email') }}">
                    <label for="email">Alamat Email</label>
                    @error("email")
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>   
                @enderror
                </div>
                <div class="form-floating">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password"
                     required>
                    <label for="password">Password</label>
                </div>
                <div class="checkbox mb-3">
                    <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember Me</label>
                  </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
            </form>
                <small class="mt-3"><a href="/register">Belum daftar akun ?</a></small>
        </main>
    </div>
</div>
    
@endsection