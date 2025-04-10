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
    <div class="card-body">
        <table id="apartmentTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Apartment No</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $owner_id = $_SESSION['login_id']; // Assuming login_id is owner
                $qry = $conn->query("SELECT a.*, c.name as category, a.manager_id FROM apartments a 
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
                    <td>
                        <button class="btn btn-primary btn-sm view-details" 
                            data-id="<?= $row['id'] ?>"
                            data-number="<?= $row['number'] ?>"
                            data-category="<?= $row['category'] ?>"
                            data-description="<?= $row['description'] ?>"
                            data-price="<?= $row['price'] ?>"
                            data-manager="<?= $row['manager_id'] ?>"
                            data-toggle="modal" data-target="#apartmentDetailsModal">
                            Assign Manager
                        </button>
                        <button class="btn btn-warning btn-sm edit-apartment"
                            data-id="<?= $row['id'] ?>"
                            data-number="<?= $row['number'] ?>"
                            data-category-id="<?= $row['category_id'] ?>"
                            data-description="<?= $row['description'] ?>"
                            data-price="<?= $row['price'] ?>"
                            data-toggle="modal" data-target="#apartmentModal">
                            Edit
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="apartmentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="manage-apartment">
                <div class="modal-header">
                    <h5 class="modal-title" id="apartmentModalLabel">Add Apartment</h5>
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
                    <button type="submit" class="btn btn-primary" id="apartmentModalSubmit">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Apartment Details Modal -->
<div class="modal fade" id="apartmentDetailsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Apartment Details</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><strong>Number:</strong> <span id="detail-number"></span></p>
        <p><strong>Category:</strong> <span id="detail-category"></span></p>
        <p><strong>Description:</strong> <span id="detail-description"></span></p>
        <p><strong>Price:</strong> $<span id="detail-price"></span></p>
        <div class="form-group">
            <label>Assign Manager</label>
            <select id="detail-manager" class="form-control"></select>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="detail-apartment-id">
        <button type="button" class="btn btn-primary" id="save-manager">Save Manager</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.0/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vfs-fonts/2.0.3/vfs_fonts.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.0/js/buttons.html5.min.js"></script>

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
    $('#apartmentTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'csvHtml5',
                text: 'Export CSV',
                className: 'btn btn-success'
            },
            {
                extend: 'excelHtml5',
                text: 'Export Excel',
                className: 'btn btn-success'
            },
            {
                extend: 'pdfHtml5',
                text: 'Export PDF',
                className: 'btn btn-danger'
            }
        ],
        responsive: true
    });

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

    $('.edit-apartment').click(function () {
        const id = $(this).data('id');
        const number = $(this).data('number');
        const categoryId = $(this).data('category-id');
        const description = $(this).data('description');
        const price = $(this).data('price');

        $('#apartmentModalLabel').text('Edit Apartment');
        $('#apartmentModalSubmit').text('Update');
        $('#manage-apartment input[name="id"]').val(id);
        $('#manage-apartment input[name="number"]').val(number);
        $('#manage-apartment select[name="category_id"]').val(categoryId);
        $('#manage-apartment textarea[name="description"]').val(description);
        $('#manage-apartment input[name="price"]').val(price);
    });

    $('#apartmentModal').on('hidden.bs.modal', function () {
        $('#apartmentModalLabel').text('Add Apartment');
        $('#apartmentModalSubmit').text('Save');
        $('#manage-apartment')[0].reset();
        $('#manage-apartment input[name="id"]').val('');
    });

    $('.view-details').click(function () {
        const apartmentId = $(this).data('id');
        const number = $(this).data('number');
        const category = $(this).data('category');
        const description = $(this).data('description');
        const price = $(this).data('price');
        const currentManager = $(this).data('manager');

        $('#detail-number').text(number);
        $('#detail-category').text(category);
        $('#detail-description').text(description);
        $('#detail-price').text(parseFloat(price).toFixed(2));
        $('#detail-apartment-id').val(apartmentId);

        $.ajax({
            url: 'ajax.php?action=get_managers',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                var managers = response.data;
                let dropdown = $('#detail-manager');
                dropdown.empty();
                dropdown.append('<option value="">Select Manager</option>');
                managers.forEach(function (manager) {
                    let selected = manager.id == currentManager ? 'selected' : '';
                    dropdown.append(`<option value="${manager.id}" ${selected}>${manager.name}</option>`);
                });
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    $('#save-manager').click(function () {
        const apartmentId = $('#detail-apartment-id').val();
        const managerId = $('#detail-manager').val();

        if (!managerId) return alert("Please select a manager.");

        $.ajax({
            url: 'ajax.php',
            method: 'POST',
            data: {
                action: 'assign_manager',
                apartment_id: apartmentId,
                manager_id: managerId
            },
            success: function (resp) {
                console.log('Raw response:', resp);
                let res;
                try {
                    res = JSON.parse(resp);
                } catch (e) {
                    console.error("Error parsing response", e);
                    alert("Error: The response is not valid JSON.");
                    return;
                }
                if (res.status === 'success') {
                    alert_toast("Manager assigned successfully!", 'success');
                    $('#apartmentDetailsModal').modal('hide');
                    setTimeout(() => location.reload(), 1200);
                } else {
                    alert_toast(res.message, 'error');
                }
            },
            error: function (xhr) {
                alert('Failed to assign manager');
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
