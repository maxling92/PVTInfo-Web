document.addEventListener('DOMContentLoaded', function () {
    var deleteButtons = document.querySelectorAll('.delete-btn');
    var editButtons = document.querySelectorAll('.edit-btn');

    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var id = button.getAttribute('data-id');
            var deleteForm = document.querySelector('#deleteForm');
            deleteForm.action = '/datapengirim/' + id;
        });
    });

    editButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var id = button.getAttribute('data-id');
            var namaObservant = button.getAttribute('data-nama-observant');
            var jabatan = button.getAttribute('data-jabatan');
            var tgllahir = button.getAttribute('data-tgllahir');

            // Set the form data in the modal
            document.getElementById('editId').value = id;
            document.getElementById('editNamaObservant').value = namaObservant;
            document.getElementById('editJabatan').value = jabatan;
            document.getElementById('editTgllahir').value = tgllahir;

            // Show the modal
            var editModal = new bootstrap.Modal(document.getElementById('EditPengirimModal'));
            editModal.show();
        });
    });

    document.getElementById('editForm').addEventListener('submit', function (event) {
        event.preventDefault();

        var id = document.getElementById('editId').value;
        var namaObservant = document.getElementById('editNamaObservant').value;
        var jabatan = document.getElementById('editJabatan').value;
        var tgllahir = document.getElementById('editTgllahir').value;

        var formattedTgllahir = formatDateForBackend(tgllahir);

        fetch('/datapengirim/' + id, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                nama_observant: namaObservant,
                jabatan: jabatan,
                tgllahir: formattedTgllahir
            })
        })
        .then(() => {
            var editModal = bootstrap.Modal.getInstance(document.getElementById('EditPengirimModal'));
            editModal.hide();

            // Redirect to the data view page
            window.location.href = '/Data';
        })
        .catch(error => {
            console.error('Error updating data:', error);
            alert('Error updating data. Please try again.');
        });
    });

    function formatDateForBackend(dateStr) {
        var parts = dateStr.split('-');
        return parts[2] + '-' + parts[1] + '-' + parts[0]; 
    }
 
});