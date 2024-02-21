
function fetchUserData() {
    $.ajax({
        url: '/users',
        method: 'GET'
    }).done(function(users) {
        $('#userList').html('');
        users.forEach(function(user) {
            const row = createUserRow(user);
            $('#userList').append(row);
        });
    }).fail(function(xhr, status, error) {
        console.error(error);
    });
}

function getUserDataFromForm() {
    return {
        name: $('#name').val(),
        first_name: $('#first_name').val(),
        last_name: $('#last_name').val(),
        birth_date: $('#birth_date').val(),
        group: $('#group').val()

    };
}

function addUser() {
    const userData = getUserDataFromForm();
    $.ajax({
        url: '/users/create',
        type: 'POST',
        dataType : "json",
        data     : userData,
    }).done(function(response) {
        if (!response.success) {
            // If errors
            response.errors.forEach(function(error) {
                // Show error description
                $('#' + error.field).addClass('input-error');
                $('#' + error.field + '_error').text(error.message);
            });
        } else {
            // If user added
            console.log('User is added. User ID:', response.userId);
            $('.close').trigger('click');
            fetchUserData();
        }
    }).fail(function(xhr, status, error) {
        console.error('Error in add user method:', error);
    });
}

function createUserRow(user) {
    let row = '<tr>';
    row += '<th scope="row">' + user.id + '</th>';
    row += '<td>' + user.name + '</td>';
    row += '<td>' + user.first_name + '</td>';
    row += '<td>' + user.last_name + '</td>';
    row += '<td>' + user.birth_date + '</td>';
    row += '<td>' + user.group_name + '</td>';
    row += '<td>';
    row += '<button type="button" class="btn btn-info btn-sm viewBtn mx-2" data-id="' + user.id + '">View</button>';
    row += '<button type="button" class="btn btn-danger btn-sm deleteBtn mx-2" data-id="' + user.id + '">Delete</button>';
    row += '<button type="button" class="btn btn-primary btn-sm editBtn mx-2" data-id="' + user.id + '">Edit</button>';
    row += '</td>';
    row += '</tr>';
    return row;
}

function showUserDetails(userId) {
    $.ajax({
        url: '/users/' + userId,
        type: 'GET',
        dataType: 'json',
    }).done(function(response) {
        // Prepare modal Content
        const modalContent = `
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> ${response.id}</p>
                <p><strong>Name:</strong> ${response.name}</p>
                <p><strong>First Name:</strong> ${response.first_name}</p>
                <p><strong>Last Name:</strong> ${response.last_name}</p>
                <p><strong>Birth Date:</strong> ${response.birth_date}</p>
                <p><strong>Group:</strong> ${response.group_name}</p>
            </div>
        `;

        $('#userDetailsContent').html(modalContent);

        // Show modal
        $('#userDetailsModal, .user-details-content').css('display', 'block');
    }).fail(function(xhr, status, error) {
        console.error('Error in get User Data method:', error);
    });
}

function showUserForm(userData = null) {
    let groupOptions = '';
    getAllGroups(function(groups) {
        groups.forEach(group => {
            let selected = userData && userData.group_id == group.id ? 'selected' : ''; // if userData.group == group.id -> selected
            groupOptions += `<option value="${group.id}" ${selected}>${group.name}</option>`;
        });

        const modalContent = `
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <label for="name">Name:</label><br>
                    <input type="text" id="name" name="name" class="form-control" value="${userData ? userData.name : ''}"><span id="name_error"></span>
                </div>
                <div>
                    <label for="first_name">First Name:</label><br>
                    <input type="text" id="first_name" name="first_name" class="form-control" value="${userData ? userData.first_name : ''}"><span id="first_name_error"></span>
                </div>
                <div>
                    <label for="last_name">Last Name:</label><br>
                    <input type="text" id="last_name" name="last_name" class="form-control" value="${userData ? userData.last_name : ''}"><span id="last_name_error"></span>
                </div>
                <div>
                    <label for="birth_date">Date of birth:</label><br>
                    <input type="date" id="birth_date" name="birth_date" class="form-control" value="${userData ? userData.birth_date : ''}"><span id="birth_date_error"></span>
                </div>
                <div>
                    <label for="group">Group:</label><br>
                    <select id="group" name="group" class="form-control">${groupOptions}</select>
                </div>
                <div>
                    <button id="${userData ? 'updateButton' : 'sendButton'}" class="btn btn-primary" ${userData ? 'data-id="' + userData.id + '"' : ''}>${userData ? 'Save Changes' : 'Add User'}</button>
                </div>
            </div>
        `;

        $('#userDetailsContent').html(modalContent);

        $('#userDetailsModal, .user-details-content').css('display', 'block');
    });
}

function deleteUser(userId) {
    $.ajax({
        url: '/users/' + userId,
        type: 'DELETE',
        dataType : "json",
    }).done(function(response) {
        if (!response) {
            console.log('Huston, we have problem!');
        } else {
            console.log('User deleted.');
            $('.close').trigger('click');
            fetchUserData();
        }
    }).fail(function(xhr, status, error) {
        console.error('Errors delete User Method:', error);
    });
}

function showEditUserForm(userId) {
    $.ajax({
        url: '/users/' + userId,
        type: 'GET',
        dataType: 'json',
    }).done(function(response) {
        if (!response) {
            console.log('Huston, we have problem!');
        } else {
            showUserForm(response);
        }
    }).fail(function(xhr, status, error) {
        console.error('Errors get User Data Method:', error);
    });
}

function updateUser(userId) {
    const userData = getUserDataFromForm();
    $.ajax({
        url: '/users/' + userId,
        type: 'POST',
        data: userData,
        dataType: 'json',
    }).done(function(response) {
        if (!response.success) {
            console.log('Huston, we have problem!');
        } else {
            // User Data saved
            console.log('User Data saved');
            $('.close').trigger('click');
            fetchUserData();
        }
    }).fail(function(xhr, status, error) {
        console.error('Errors add new User Method:', error);
    });
}

function getAllGroups(callback) {
    $.ajax({
        url: '/groups',
        type: 'GET',
        dataType: "json",
    }).done(function(groups) {
        callback(groups); // Wywołaj funkcję zwrotną i przekaż pobrane grupy
    }).fail(function(xhr, status, error) {
        console.error('Errors during fetching groups:', error); // Obsłuż błąd
    });
}
