@foreach ($inputdata as $data)
<div class="modal fade" id="EditPengirimModal" tabindex="-1" aria-labelledby="editPengirimLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPengirimLabel">Edit Data Pengirim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk edit datapengirim -->
                <form id="editForm" action="/Data/{{ $data->id }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editId" name="id" value="{{ $data->id }}">

                    <!-- Nama Observant -->
                    <div class="form-group">
                        <label for="editNamaObservant">Nama Observant</label>
                        <input type="text" id="editNamaObservant" name="nama_observant" class="form-control" value="{{ $data->nama_observant }}" required>
                    </div>

                    <!-- Tanggal Lahir -->
                    <div class="form-group">
                        <label for="editTgllahir">Tanggal Lahir</label>
                        <input type="date" id="editTgllahir" name="tgllahir" class="form-control" value="{{ $data->tgllahir }}" required>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

 @endforeach

