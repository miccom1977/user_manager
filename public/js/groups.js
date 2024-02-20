function fetchGroupData() {
    $.ajax({
        url: '/groups',
        method: 'GET'
    }).done(function(groups) {
        $('#groupList').html('');
        groups.forEach(function(group) {
            const row = createGroupRow(group);
            $('#groupList').append(row);
        });
    }).fail(function(xhr, status, error) {
        console.error(error);
    });
}

function getGroupDataFromForm() {
    const groupData = {
        name: $('#name').val()
    };
    return groupData;
}

function addGroup() {
    const groupData = getGroupDataFromForm();
    $.ajax({
        url: '/groups/create',
        type: 'POST',
        dataType : "json",
        data     : groupData,
    }).done(function(response) {
        if (!response.success) {
            // If errors
            response.errors.forEach(function(error) {
                // Show error description
                $('#' + error.field).addClass('input-error');
                $('#' + error.field + '_error').text(error.message);
            });
        } else {
            // If group added
            console.log('group is added. group ID:', response.groupId);
            $('.close').trigger('click');
            fetchGroupData();
        }
    }).fail(function(xhr, status, error) {
        console.error('Error in add group method:', error);
    });
}

function createGroupRow(group) {
    let row = '<tr>';
    row += '<th scope="row">' + group.id + '</th>';
    row += '<td>' + group.name + '</td>';
    row += `<td>${group.users_names ? group.users_names : "no users in this group"}</td>`;
    row += '<td>';
    row += '<button type="button" class="btn btn-info btn-sm viewGroupBtn mx-2" data-id="' + group.id + '">View</button>';
    row += '<button type="button" class="btn btn-danger btn-sm deleteGroupBtn mx-2" data-id="' + group.id + '">Delete</button>';
    row += '<button type="button" class="btn btn-primary btn-sm editGroupBtn mx-2" data-id="' + group.id + '">Edit</button>';
    row += '</td>';
    row += '</tr>';
    return row;
}

function showGroupDetails(groupId) {
    $.ajax({
        url: '/groups/' + groupId,
        type: 'GET',
        dataType: 'json',
    }).done(function(response) {
        if (!response) {
            console.log('Huston, we have problem!');
        } else {
            // Prepare modal Content
            const modalContent = `
                <div class="modal-header">
                    <h5 class="modal-title">group Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>ID:</strong> ${response.id}</p>
                    <p><strong>Name:</strong> ${response.name}</p>
                </div>
            `;

            $('#groupDetailsContent').html(modalContent);

            // Show modal
            $('#groupDetailsModal, .group-details-content').css('display', 'block');
        }
    }).fail(function(xhr, status, error) {
        console.error('Error in get group Data method:', error);
    });
}

function showGroupForm(groupData = null) {
    const modalContent = `
        <div class="modal-header">
            <h5 class="modal-title">group Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div>
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" class="form-control" value="${groupData ? groupData.name : ''}"><span id="name_error"></span>
            </div>
            <div>
                <button id="${groupData ? 'updateGroupButton' : 'sendGroupButton'}" class="btn btn-primary" ${groupData ? 'data-id="' + groupData.id + '"' : ''}>${groupData ? 'Save Changes' : 'Add group'}</button>
            </div>
        </div>
    `;

    $('#groupDetailsContent').html(modalContent);

    $('#groupDetailsModal, .group-details-content').css('display', 'block');
}

function deleteGroup(groupId) {
    $.ajax({
        url: '/groups/' + groupId,
        type: 'DELETE',
        dataType : "json",
    }).done(function(response) {
        if (!response) {
            console.log('Huston, we have problem!');
        } else {
            console.log('group deleted.');
            $('.close').trigger('click');
            fetchGroupData();
        }
    }).fail(function(xhr, status, error) {
        console.error('Errors delete group Method:', error);
    });
}

function showEditGroupForm(groupId) {
    $.ajax({
        url: '/groups/' + groupId,
        type: 'GET',
        dataType: 'json',
    }).done(function(response) {
        if (!response) {
            console.log('Huston, we have problem!');
        } else {
            showGroupForm(response);
        }
    }).fail(function(xhr, status, error) {
        console.error('Errors get group Data Method:', error);
    });
}

function updateGroup(groupId) {
    const groupData = getGroupDataFromForm();
    $.ajax({
        url: '/groups/' + groupId,
        type: 'POST',
        data: groupData,
        dataType: 'json',
    }).done(function(response) {
        if (!response.success) {
            console.log('Huston, we have problem!');
        } else {
            // group Data saved
            console.log('group Data saved');
            $('.close').trigger('click');
            fetchGroupData();
        }
    }).fail(function(xhr, status, error) {
        console.error('Errors add new group Method:', error);
    });
}
