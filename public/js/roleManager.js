class RoleManager {
    constructor() {
        // Add event listener to all change-role buttons
        this.initializeChangeRoleButtons();
    }

    initializeChangeRoleButtons() {
        // Attach click event to all buttons with class 'change-role-btn'
        const changeRoleButtons = document.querySelectorAll('.change-role-btn');

        changeRoleButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                // Extract user data from the button's data attributes
                const userId = button.getAttribute('data-user-id');
                const userRole = button.getAttribute('data-user-role');

                // Set the values in the modal
                document.getElementById('userId').value = userId;
                document.getElementById('role').value = userRole;

                // Set the form action dynamically
                document.getElementById('changeRoleForm').setAttribute('action', `/Management/assign-role/${userId}`);
            });
        });
    }
}

// Initialize RoleManager on page load
document.addEventListener('DOMContentLoaded', () => {
    new RoleManager();
});
