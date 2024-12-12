// resources/js/profile.js

$(document).ready(function() {
    // Function to populate modal fields with current user data
    $('#editUserModal').on('show.bs.modal', function(event) {
        var modal = $(this);
        modal.find('#editName').val('{{ auth()->user()->name }}');
        modal.find('#editEmail').val('{{ auth()->user()->email }}');
    });

    // Function to handle form submission
    $('#editUserForm').on('submit', function(event) {
        event.preventDefault();
        
        var form = $(this);
        var formData = form.serialize();
        var url = form.attr('action');

        // Ajax request to update user profile
        $.ajax({
            type: 'PUT',
            url: url,
            data: formData,
            success: function(response) {
                $('#editUserModal').modal('hide'); // Hide modal on success
                // Optionally, you can update the displayed user data on the profile page here
                alert('Profile updated successfully.');
                location.reload(); // Refresh the page or update the displayed data
            },
            error: function(error) {
                console.error('Error updating profile:', error);
                alert('Failed to update profile. Please try again.');
            }
        });
    });

    $('#deleteUserButton').on('click', function(event) {
        event.preventDefault();
        if (confirm('Apakah Anda yakin ingin menghapus akun Anda?')) {
            $.ajax({
                url: '/user/' + userId, // Pastikan userId adalah ID pengguna yang ingin dihapus
                type: 'DELETE', // Metode HTTP harus DELETE
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF wajib
                },
                success: function(response) {
                    alert('Akun berhasil dihapus.');
                    window.location.href = '/'; // Arahkan ke halaman lain jika diperlukan
                },
                error: function(error) {
                    console.error('Error:', error);
                    alert('Gagal menghapus akun. Coba lagi.');
                }
            });
        }
    });
    
});
