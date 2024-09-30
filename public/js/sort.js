document.addEventListener('DOMContentLoaded', function() {
    const sortIcons = document.querySelectorAll('.sort-icon');
    const sortByField = document.getElementById('sort_by');
    const sortOrderField = document.getElementById('sort_order');
    
    sortIcons.forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.preventDefault();
            const currentSortBy = sortByField.value;
            const currentSortOrder = sortOrderField.value;
            const newSortBy = this.getAttribute('data-sort');

            if (currentSortBy === newSortBy) {
                if (currentSortOrder === 'asc') {
                    sortOrderField.value = 'desc';
                } else if (currentSortOrder === 'desc') {
                    sortOrderField.value = '';
                    sortByField.value = '';
                } else {
                    sortOrderField.value = 'asc';
                }
            } else {
                sortByField.value = newSortBy;
                sortOrderField.value = 'asc';
            }

            document.getElementById('filter-form').submit();
        });
    });
});
