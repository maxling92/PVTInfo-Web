<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
                        <input type="date" class="form-control flatpickr" id="tgllahir" name="tgllahir" placeholder="dd-MM-yyyy" required>
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr(".flatpickr", {
            dateFormat: "d-m-Y" // Format dd-MM-yyyy
        });
    });
</script>
