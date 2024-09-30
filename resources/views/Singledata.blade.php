@extends('layouts.Banyakpengukuran')

@section('container')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $title }}</h1>
</div>

<form method="GET" action="{{ $searchAction }}" class="mb-3" id="filter-form">
    <input type="hidden" name="sort_by" id="sort_by" value="{{ $sortBy }}">
    <input type="hidden" name="sort_order" id="sort_order" value="{{ $sortOrder }}">
    <div class="row">
        <div class="col-md-6">
            <div class="row align-items-end">
                <div class="col-md-6">
                    <label for="search" class="form-label">Cari</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ $searchQuery }}">
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="tanggal_from" class="form-label">Tanggal From</label>
                            <input type="date" name="tanggal_from" id="tanggal_from" class="form-control" value="{{ $filterTanggalFrom }}">
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_to" class="form-label">Tanggal To</label>
                            <input type="date" name="tanggal_to" id="tanggal_to" class="form-control" value="{{ $filterTanggalTo }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label for="jenistest" class="form-label">Jenis Test</label>
                    <select id="jenistest" name="jenistest" class="form-select">
                        <option value="">Filter by Jenis Test</option>
                        <option value="VISUAL" {{ $filterJenistest == 'VISUAL' ? 'selected' : '' }}>VISUAL</option>
                        <option value="AUDIO" {{ $filterJenistest == 'AUDIO' ? 'selected' : '' }}>AUDIO</option>
                    </select>
                </div>
                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-primary">Apply</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">Nama Data
                    <a href="#" class="sort-icon" data-sort="namadata">
                        @if($sortBy == 'namadata' && $sortOrder == 'asc')
                            ▲
                        @elseif($sortBy == 'namadata' && $sortOrder == 'desc')
                            ▼
                        @else
                            ↕
                        @endif
                    </a>
                </th>
                <th scope="col">Tanggal
                    <a href="#" class="sort-icon" data-sort="tanggal">
                        @if($sortBy == 'tanggal' && $sortOrder == 'asc')
                            ▲
                        @elseif($sortBy == 'tanggal' && $sortOrder == 'desc')
                            ▼
                        @else
                            ↕
                        @endif
                    </a>
                </th>
                <th scope="col">Lokasi
                    <a href="#" class="sort-icon" data-sort="lokasi">
                        @if($sortBy == 'lokasi' && $sortOrder == 'asc')
                            ▲
                        @elseif($sortBy == 'lokasi' && $sortOrder == 'desc')
                            ▼
                        @else
                            ↕
                        @endif
                    </a>
                </th>
                <th scope="col">Jenis Test</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datapengukurans as $datapengukuran)
                <tr>
                    <td><a href="{{ route('data.Hasil', ['namadata' => $datapengukuran->namadata]) }}">{{ $datapengukuran->namadata }}</a></td>
                    <td>{{ $datapengukuran->tanggal }}</td>
                    <td>{{ $datapengukuran->lokasi }}</td>
                    <td>{{ $datapengukuran->jenistest }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="{{ asset('js/sort.js') }}"></script>
@endsection
