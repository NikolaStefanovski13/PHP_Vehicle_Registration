function editRegistration(id) {
    window.location.href = 'edit_registration.php?id=' + id;
}

function deleteRegistration(id) {
    if (confirm("Are you sure you want to delete this registration?")) {
        fetch('delete_registration.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row from the table
                document.querySelector(`tr[data-id="${id}"]`).remove();
                alert("Registration deleted successfully");
            } else {
                alert("Error deleting registration: " + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred while deleting the registration");
        });
    }
}

function extendRegistration(id) {
    const newDate = prompt("Enter new expiration date (YYYY-MM-DD):");
    if (newDate) {
        fetch('extend_registration.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}&new_date=${newDate}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Registration extended successfully");
                location.reload();
            } else {
                alert("Error extending registration: " + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred while extending the registration");
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('form[method="GET"]');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchTerm = this.querySelector('input[name="search"]').value;
            window.location.href = `admin.php?search=${encodeURIComponent(searchTerm)}`;
        });
    }
});


function editRegistration(id) {
    window.location.href = `edit_registration.php?id=${id}`;
}

function deleteRegistration(id) {
    if (confirm("Are you sure you want to delete this registration?")) {
        fetch('delete_registration.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${id}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`tr[data-id="${id}"]`).remove();
            } else {
                alert("Error deleting registration: " + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}