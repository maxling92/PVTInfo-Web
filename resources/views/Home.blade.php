@extends('layouts.main')

@section('container')
  <h1>Home page</h1> 
  
  @if(isset($noRoleMessage))
        <div class="alert alert-warning">
            {{ $noRoleMessage }}
        </div>
    @endif
@endsection   
