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
								<th class="text-center">Name</th>
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
									<td><?php echo ucwords($row['name']) ?></td>
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
						<label for="name">Full Name</label>
						<input type="text" name="name" id="name" class="form-control" required>
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
		});

		// Edit user button
		$('.edit_user').click(function() {
			var id = $(this).data('id');
			$.ajax({
				url: 'get_user.php?id=' + id,
				method: 'GET',
				success: function(resp) {
					if (resp) {
						resp = JSON.parse(resp);
						$('#userModalLabel').text('Edit User');
						$('#user_id').val(resp.id);
						$('#name').val(resp.name);
						$('#username').val(resp.username);
						$('#type').val(resp.type);
						$('#userModal').modal('show');
					}
				}
			});
		});

		// Form submission
		$('#manage-user').submit(function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			var id = $('#user_id').val();
			var url = id ? 'update_user.php' : 'create_user.php';

			$.ajax({
				url: url,
				method: 'POST',
				data: formData,
				success: function(resp) {
					resp = JSON.parse(resp);
					if (resp.status == 'success') {
						$('#msg').html('<div class="alert alert-success">' + resp.message + '</div>');
						setTimeout(function() {
							$('#userModal').modal('hide');
							location.reload();
						}, 2000);
					} else {
						$('#msg').html('<div class="alert alert-danger">' + resp.message + '</div>');
					}
				}
			});
		});

		// Delete user
		$('.delete_user').click(function() {
			var id = $(this).data('id');
			if (confirm('Are you sure you want to delete this user?')) {
				$.ajax({
					url: 'delete_user.php',
					method: 'POST',
					data: {
						id: id
					},
					success: function(resp) {
						location.reload();
					}
				});
			}
		});
	});
</script>