<?php include('db_connect.php'); ?>
<div class="container-fluid mt-5">
	<!-- Button to Open Modal -->
	<div class="row mb-3">
		<div class="col-md-12 text-right">
			<button class="btn btn-primary" data-toggle="modal" data-target="#apartmentModal">
				+ Add Apartment
			</button>
		</div>
	</div>

	<!-- List of Apartments (filtered by owner) -->
	<div class="card">
		<div class="card-header">My Apartments</div>
		<div class="card-body">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>Apartment No</th>
						<th>Category</th>
						<th>Price</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$owner_id = $_SESSION['login_id']; // Assuming login_id is owner
						$qry = $conn->query("SELECT a.*, c.name as category FROM apartments a 
											LEFT JOIN categories c ON a.category_id = c.id 
											WHERE a.owner_id = $owner_id ORDER BY a.id DESC");
						$i = 1;
						while($row = $qry->fetch_assoc()):
					?>
					<tr>
						<td><?= $i++ ?></td>
						<td><?= $row['number'] ?></td>
						<td><?= $row['category'] ?></td>
						<td><?= number_format($row['price'], 2) ?></td>
						<td><?= $row['description'] ?></td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="apartmentModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form id="manage-apartment">
				<div class="modal-header">
					<h5 class="modal-title">Add Apartment</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<div class="modal-body">
					<div class="form-group" id="msg"></div>
					<div class="row">
						<div class="col-md-6">
							<input type="hidden" name="id">
							<div class="form-group">
								<label>Apartment No</label>
								<input type="text" class="form-control" name="number" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Category</label>
								<select name="category_id" class="custom-select" required>
									<?php 
									$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
									while($row= $categories->fetch_assoc()):
									?>
										<option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
									<?php endwhile; ?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Description</label>
								<textarea name="description" rows="4" class="form-control" required></textarea>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Price</label>
								<input type="number" class="form-control text-right" name="price" step="any" required>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Save</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>


<style>
	td {
		vertical-align: middle !important;
	}
	td p {
		margin: unset;
		padding: unset;
		line-height: 1em;
	}
</style>
<script>
$(document).ready(function(){
    $('#manage-apartment').submit(function (e) {
        e.preventDefault();
        start_load();
        $('#msg').html('');

        var formData = new FormData($(this)[0]);

        $.ajax({
            url: 'ajax.php?action=add_apartment',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            success: function (resp) {
                console.log(resp);
                let res = JSON.parse(resp);
                if (res.status === 'success') {
                    alert_toast(res.message, 'success');
                    setTimeout(function () {
                        location.reload(); // Reload page to show updated apartment list
                    }, 1500);
                } else {
                    $('#msg').html('<div class="alert alert-danger">' + res.message + '</div>');
                    end_load();
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                alert('Error: ' + error);
                end_load();
            }
        });
    });
});
</script>




