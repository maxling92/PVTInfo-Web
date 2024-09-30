@extends('layouts.main')

@section('container')
<div class="container">
    <h1>{{ $title }}</h1>
    <p>Name: {{ auth()->user()->name }}</p>
    <p>Email: {{ auth()->user()->email }}</p>
    <p>Nama Perusahaan: {{ auth()->user()->nama_perusahaan }}</p>

    <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editUserModal" >Edit</button>
    <button class="btn btn-sm btn-danger delete-btn" data-bs-toggle="modal" data-bs-target="#deleteUserModal">Hapus</button>
</div>

@include('HapusUser')
@include('Edituser')
@endsection

@section('scripts')
    <script src="{{ asset('js/Profile.js') }}"></script>
@endsection