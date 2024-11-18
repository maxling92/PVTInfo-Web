document.addEventListener('DOMContentLoaded', function () {
    var deleteButtons = document.querySelectorAll('.delete-btn');
    var editButtons = document.querySelectorAll('.edit-btn');
    var editModalElement = document.getElementById('EditPengirimModal');
    
    // Listen for the modal close event to remove the overlay if needed
    editModalElement.addEventListener('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        var modalsBackdrops = document.getElementsByClassName('modal-backdrop');
        for (var i = 0; i < modalsBackdrops.length; i++) {
            modalsBackdrops[i].parentNode.removeChild(modalsBackdrops[i]);
        }
    });

    // Pengecekan dan pengambilan CSRF token
    var csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
    if (!csrfTokenElement) {
        console.error('CSRF token not found in the DOM.');
        return;  // Hentikan eksekusi jika CSRF token tidak ditemukan
    }
    var csrfToken = csrfTokenElement.getAttribute('content');

    // Handle delete button
    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var id = button.getAttribute('data-id');
            var deleteForm = document.querySelector('#deleteForm');

            if (deleteForm) {
                // Set the delete action with the correct ID
                deleteForm.action = '/Data/' + id;
            } else {
                console.error('Delete form not found.');
            }
        });
    });

    // Handle edit button click
    editButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var id = button.getAttribute('data-id');
            var namaObservant = button.getAttribute('data-nama-observant');
            var tgllahir = button.getAttribute('data-tgllahir');

            // Set the form data in the modal
            document.getElementById('editId').value = id;
            document.getElementById('editNamaObservant').value = namaObservant;
            document.getElementById('editTgllahir').value = tgllahir;

            var editForm = document.getElementById('editForm');
            editForm.action = '/Data/' + id;

            // Show the modal
            var editModal = new bootstrap.Modal(document.getElementById('EditPengirimModal'));
            editModal.show();
        });
    });
});
