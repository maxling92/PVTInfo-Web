@extends('layouts.dataview')

@section('container')
    <h1>{{ $title }}</h1>

    <!-- Filter Form -->
    <form id="filter-form" method="GET" action="{{ $action }}">
        <div class="row">
            <!-- Filter By Kategori Field -->
            <div class="col-md-3">
                <select name="kategori" class="form-control">
                    <option value="">Filter By Kategori</option>
                    @foreach($filterOptions as $option)
                        <option value="{{ $option['value'] }}" {{ request('kategori') == $option['value'] ? 'selected' : '' }}>{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date Range Fields -->
            <div class="col-md-3">
                <input type="date" name="tgl_from" class="form-control" value="{{ request('tgl_from') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="tgl_to" class="form-control" value="{{ request('tgl_to') }}">
            </div>

            <!-- Filter Button -->
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary" id="filterBtn">Filter</button>
            </div>
        </div>

        <!-- Hidden Fields for Sorting -->
        <input type="hidden" id="sort_by" name="sort_by" value="{{ request('sort_by') }}">
        <input type="hidden" id="sort_order" name="sort_order" value="{{ request('sort_order', 'asc') }}">
    </form>

    <!-- Data Table -->
    <table class="table">
        <thead>
            <tr>
                <th>
                    Nama Observant
                    <a href="#" class="sort-icon" data-sort="nama_observant">
                        @if(request('sort_by') == 'nama_observant')
                            @if(request('sort_order') == 'asc')
                                ▲
                            @else
                                ▼
                            @endif
                        @else
                            ↕
                        @endif
                    </a>
                </th>
                <th>
                    Jabatan
                    <a href="#" class="sort-icon" data-sort="jabatan">
                        @if(request('sort_by') == 'jabatan')
                            @if(request('sort_order') == 'asc')
                                ▲
                            @else
                                ▼
                            @endif
                        @else
                            ↕
                        @endif
                    </a>
                </th>
                <th>
                    Tanggal Lahir
                    <a href="#" class="sort-icon" data-sort="tgllahir">
                        @if(request('sort_by') == 'tgllahir')
                            @if(request('sort_order') == 'asc')
                                ▲
                            @else
                                ▼
                            @endif
                        @else
                            ↕
                        @endif
                    </a>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($inputdata as $data)
                <tr data-id="{{ $data->id }}">
                    <td data-field="nama_observant">
                        <a href="{{ route('datapengukuran.index', ['nama_observant' => $data->nama_observant]) }}">
                            {{ $data->nama_observant }}
                        </a>
                    </td>
                    <td data-field="jabatan">{{ $data->jabatan }}</td>
                    <td data-field="tgllahir">{{ \Carbon\Carbon::parse($data->tgllahir)->format('d-m-Y') }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#EditPengirim" data-id="{{ $data->id }}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-bs-toggle="modal" data-bs-target="#DeletePengirim" data-id="{{ $data->id }}">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $inputdata->links() }}

    @include('DataPengirim.DeletePengirim')
    @include('DataPengirim.EditPengirim')

    <script src="{{ asset('js/sort.js') }}"></script>
    <script src="{{ asset('js/Datapengirim.js') }}"></script>
@endsection
