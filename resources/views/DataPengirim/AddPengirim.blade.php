<div class="modal fade" id="AddPengirim" tabindex="-1" aria-labelledby="AddPengirimLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="AddPengirimLabel">Tambah Data Pengirim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('datapengirim.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_observant" class="form-label">Nama:</label>
                        <input type="text" class="form-control" id="nama_observant" name="nama_observant" required>
                    </div>
                    <div class="mb-3">
                        <label for="tgllahir" class="form-label">Tanggal Lahir:</label>
                        <input type="text" class="form-control" id="tgllahir" name="tgllahir" placeholder="dd-MM-yyyy" required>
                    </div>
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Posisi:</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">OK</button>
                </div>
            </form>
        </div>
    </div>
</div>
