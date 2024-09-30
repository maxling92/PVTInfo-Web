@foreach ($inputdata as $data)
<div class="modal fade" id="EditPengirimModal" tabindex="-1" aria-labelledby="editPengirimLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPengirimLabel">Edit Datapengirim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editId">
                    <div class="mb-3">
                        <label for="editNamaObservant" class="form-label">Nama Observant</label>
                        <input type="text" class="form-control" id="editNamaObservant">
                    </div>
                    <div class="mb-3">
                        <label for="editJabatan" class="form-label">Jabatan</label>
                        <select class="form-control" id="editJabatan">
                            <option value="Supir Senior">Supir Senior</option>
                            <option value="Supir Junior">Supir Junior</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editTgllahir" class="form-label">Tanggal Lahir</label>
                        <input type="text" class="form-control" id="editTgllahir">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

 @endforeach

