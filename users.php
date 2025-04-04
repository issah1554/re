<?php
include 'db_connect.php';
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header bg-primary text-white">
					<b>List of Users</b>
					<button class="btn btn-light btn-sm col-sm-2 float-right" data-toggle="modal" data-target="#userModal" id="new_user">
						<i class="fa fa-plus"></i> New User
					</button>
				</div>
				<div class="card-body">
					<table class="table table-striped table-bordered" id="users-table">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">Full Name</th>
								<th class="text-center">Username</th>
								<th class="text-center">Role</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$type = array("", "Admin", "Staff", "Alumnus/Alumna");
							$users = $conn->query("SELECT * FROM users ORDER BY name ASC");
							$i = 1;
							while ($row = $users->fetch_assoc()):
							?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td><?php echo ucwords($row['first_name']) . " " . ucwords($row['last_name']) ?></td>
									<td><?php echo $row['username'] ?></td>
									<td><?php echo $type[$row['type']] ?></td>
									<td class="text-center">
										<div class="btn-group">
											<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Action
											</button>
											<div class="dropdown-menu">
												<a class="dropdown-item edit_user" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>Edit</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item delete_user" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>Delete</a>
											</div>
										</div>
									</td>
								</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="userModalLabel">Manage User</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="msg"></div>
				<form action="" id="manage-user">

					<input type="hidden" name="id" id="user_id">

					<div class="form-group">
						<label for="name">First Name</label>
						<input type="text" name="first_name" id="first_name" class="form-control" required>
					</div>
					<div class="form-group">
						<label for="name">Last Name</label>
						<input type="text" name="last_name" id="last_name" class="form-control" required>
					</div>

					<div class="form-group">
						<label for="username">Username</label>
						<input type="text" name="username" id="username" class="form-control" required autocomplete="off">
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" name="password" id="password" class="form-control" autocomplete="off">
						<small class="text-muted">Leave blank to keep current password</small>
					</div>
					<div class="form-group">
						<label for="type">User Type</label>
						<select name="type" id="type" class="form-control" required>
							<option value="1">Admin</option>
							<option value="2">Staff</option>
							<option value="3">Alumnus/Alumna</option>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" form="manage-user">Save</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		// Initialize DataTable
		$('#users-table').DataTable();

		// New user button
		$('#new_user').click(function() {
			$('#userModalLabel').text('New User');
			$('#manage-user')[0].reset();
			$('#user_id').val('');
			$('#msg').html('');
			$('#password').prop('required', true); // Make password required for new users
			$('#userModal').modal('show');
		});

		// Edit user button
		$(document).on('click', '.edit_user', function() {
			var id = $(this).data('id');
			$.ajax({
				url: 'ajax.php?action=get_user_id&id=' + id,
				method: 'GET',
				dataType: 'json',
				success: function(resp) {
					if (resp && !resp.status) { // Check if not an error response
						$('#userModalLabel').text('Edit User');
						$('#user_id').val(resp.id);
						$('#username').val(resp.username);
						$('#type').val(resp.type);
						$('#first_name').val(resp.first_name);
						$('#last_name').val(resp.last_name);
						$('#password').prop('required', false); // Password not required for update
						$('#userModal').modal('show');
						console.log("User data:", resp); // <-- log user data
					} else if (resp && resp.status === 'error') {
						alert(resp.message);
					}
				},
				error: function(xhr, status, error) {
					alert('Error fetching user data: ' + error);
				}
			});
		});

// Form submission
$('#manage-user').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    var id = $('#user_id').val();
    var url = id ? 'ajax.php?action=update_user' : 'ajax.php?action=create_user';

    // Add user_id to formData if it exists
    if(id) {
        formData += '&user_id=' + id;
    }

    $('#msg').html('<div class="alert alert-info">Processing...</div>');
    
    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function(resp) {
            if (resp.status == 'success') {
                $('#msg').html('<div class="alert alert-success">' + resp.message + '</div>');
                setTimeout(function() {
                    $('#userModal').modal('hide');
                    location.reload();
                }, 1500);
            } else {
                $('#msg').html('<div class="alert alert-danger">' + resp.message + '</div>');
            }
        },
        error: function(xhr, status, error) {
            $('#msg').html('<div class="alert alert-danger">Error: ' + error + '</div>');
        }
    });
});
		// Delete user
		$(document).on('click', '.delete_user', function() {
			var id = $(this).data('id');
			if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
				$.ajax({
					url: 'ajax.php?action=delete_user',
					method: 'POST',
					data: {
						id: id
					},
					dataType: 'json',
					success: function(resp) {
						if (resp.status == 'success') {
							alert(resp.message);
							location.reload();
						} else {
							alert(resp.message);
						}
					},
					error: function(xhr, status, error) {
						alert('Error: ' + error);
					}
				});
			}
		});
	});
</script>