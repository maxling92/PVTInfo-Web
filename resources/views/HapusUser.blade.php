<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action=>
          @csrf
          @method('DELETE')
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="deleteUserModalLabel">Hapus Akun</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <p>Apakah anda yakin ingin menghapus akun anda?</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-danger">Hapus</button>
              </div>
          </div>
      </form>
    </div>
  </div>