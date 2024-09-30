<div class="modal fade" id="DeletePengirim" tabindex="-1" aria-labelledby="DeletePengirimLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DeletePengirimLabel">Delete Data Pengirim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Are you sure you want to delete this data?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">OK</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function setDeleteFormAction(id) {
        const form = document.getElementById('deleteForm');
        form.action = `/datapengirim/${id}`;
    }

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            setDeleteFormAction(id);
        });
    });
</script>
