<?php include('db_connect.php'); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<head>
    <style>
        td {
            vertical-align: middle !important;
        }

        td p {
            margin: unset;
            padding: unset;
            line-height: 1em;
        }
        
        .apartment-card {
            border-left: 4px solid #4e73df;
            padding-left: 10px;
        }
        
        .badge-available {
            background-color: #28a745 !important;
        }
        
        .badge-occupied {
            background-color: #dc3545 !important;
        }
    </style>
</head>

<div class="container-fluid">
    <div class="col-lg-12 mt-5">
        <div class="row">
            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <b>Apartment Management</b>
                        <button class="btn btn-sm btn-light float-end" id="addNewApartmentBtn">
                            <i class="fas fa-plus"></i> Add New Apartment
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="apartmentsTable" class="display table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Apartment Details</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $apartments = $conn->query("
                                    SELECT 
                                        a.number AS apartment_number,
                                        a.description,
                                        a.price,
                                        a.owner_id,
                                        a.tenant_id,
                                        c.name AS category_name,
                                        u.first_name AS owner_first_name,
                                        u.last_name AS owner_last_name,
                                        c.id AS category_id,
                                        a.id AS id,
                                        t.first_name AS tenant_first_name,
                                        t.last_name AS tenant_last_name                         
                                    FROM apartments a
                                    LEFT JOIN categories c ON a.category_id = c.id
                                    INNER JOIN users u ON a.owner_id = u.id
                                    LEFT JOIN users t ON a.tenant_id = t.id
                                    WHERE a.manager_id = {$_SESSION['login_id']}
                                    ORDER BY a.number ASC
                                ");
                                
                                while ($row = $apartments->fetch_assoc()):
                                    $isOccupied = !empty($row['tenant_id']);
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td class="align-middle">
                                            <div class="apartment-card">
                                                <div class="d-flex mb-2">
                                                    <span class="text-muted me-2"><i class="fas fa-home"></i> Apartment #: &nbsp;</span>
                                                    <span><strong><?php echo $row['apartment_number'] ?></strong></span>
                                                </div>

                                                <div class="d-flex mb-2">
                                                    <span class="text-muted me-2"><i class="fas fa-tag"></i> Category: &nbsp;</span>
                                                    <span><?php echo $row['category_name'] ?></span>
                                                </div>

                                                <div class="d-flex mb-2">
                                                    <span class="text-muted me-2"><i class="fas fa-align-left"></i> Description: &nbsp;</span>
                                                    <span><?php echo $row['description'] ?: 'N/A' ?></span>
                                                </div>

                                                <div class="d-flex mb-2">
                                                    <span class="text-muted me-2"><i class="fas fa-dollar-sign"></i> Monthly Rent: &nbsp;</span>
                                                    <span>Tsh <?php echo number_format($row['price'], 2) ?></span>
                                                </div>

                                                <div class="d-flex mb-2">
                                                    <span class="text-muted me-2"><i class="fas fa-user-tie"></i> Owner: &nbsp;</span>
                                                    <span><?php echo $row['owner_first_name'] . " " . $row['owner_last_name'] ?></span>
                                                </div>
                                                
                                                <?php if($isOccupied): ?>
                                                <div class="d-flex">
                                                    <span class="text-muted me-2"><i class="fas fa-user"></i> Tenant: &nbsp;</span>
                                                    <span><?php echo $row['tenant_first_name'] . " " . $row['tenant_last_name'] ?></span>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge p-2 <?php echo $isOccupied ? 'badge-occupied' : 'badge-available'; ?>">
                                                <?php echo $isOccupied ? 'Occupied' : 'Available'; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm <?php echo $isOccupied ? 'btn-warning' : 'btn-primary'; ?> manage-tenant-btn"
                                                type="button"
                                                data-apartment-id="<?php echo $row['id'] ?>"
                                                data-apartment-number="<?php echo $row['apartment_number'] ?>"
                                                data-tenant-id="<?php echo $row['tenant_id'] ?? '' ?>">
                                                <i class="fas <?php echo $isOccupied ? 'fa-user-edit' : 'fa-user-plus'; ?>"></i>
                                                <?php echo $isOccupied ? 'Manage Tenant' : 'Assign Tenant'; ?>
                                            </button>
                                            
                                            <button class="btn btn-sm btn-info edit-apartment-btn"
                                                data-apartment-id="<?php echo $row['id'] ?>"
                                                data-apartment-number="<?php echo $row['apartment_number'] ?>"
                                                data-description="<?php echo htmlspecialchars($row['description']) ?>"
                                                data-category-id="<?php echo $row['category_id'] ?>"
                                                data-price="<?php echo $row['price'] ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>
</div>

<!-- Tenant Management Modal -->
<div class="modal fade" id="tenantModal" tabindex="-1" aria-labelledby="tenantModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="tenantForm">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="tenantModalLabel">Manage Tenant</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="apartment_id" id="apartment_id">
                    <input type="hidden" name="action" value="update_tenant">
                    
                    <div class="mb-3">
                        <label for="apartment_number" class="form-label">Apartment Number</label>
                        <input type="text" class="form-control" id="apartment_number" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tenant_id" class="form-label">Select Tenant</label>
                        <select class="form-select" name="tenant_id" id="tenant_id" required>
                            <option value="">-- Select Tenant --</option>
                            <?php
                            $tenants = $conn->query("SELECT id, first_name, last_name FROM users WHERE user_type = 'tenant'");
                            while($tenant = $tenants->fetch_assoc()):
                            ?>
                                <option value="<?php echo $tenant['id'] ?>">
                                    <?php echo $tenant['first_name'] . ' ' . $tenant['last_name'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Apartment Form Modal -->
<div class="modal fade" id="apartmentModal" tabindex="-1" aria-labelledby="apartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="apartmentForm">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="apartmentModalLabel">Apartment Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_apartment_id">
                    <input type="hidden" name="action" value="save_apartment">
                    
                    <div class="mb-3">
                        <label for="apartment_number" class="form-label">Apartment Number *</label>
                        <input type="text" class="form-control" name="apartment_number" id="apartment_number_input" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category *</label>
                        <select class="form-select" name="category_id" id="category_id" required>
                            <option value="">-- Select Category --</option>
                            <?php
                            $categories = $conn->query("SELECT id, name FROM categories");
                            while($category = $categories->fetch_assoc()):
                            ?>
                                <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="price" class="form-label">Monthly Rent (Tsh) *</label>
                        <input type="number" class="form-control" name="price" id="price" min="0" step="0.01" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Apartment</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#apartmentsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'
        ],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search apartments..."
        }
    });
    
    // Handle tenant management
    $('.manage-tenant-btn').click(function() {
        const apartmentId = $(this).data('apartment-id');
        const apartmentNumber = $(this).data('apartment-number');
        const tenantId = $(this).data('tenant-id');
        
        $('#tenantModal #apartment_id').val(apartmentId);
        $('#tenantModal #apartment_number').val(apartmentNumber);
        $('#tenantModal #tenant_id').val(tenantId);
        
        $('#tenantModal').modal('show');
    });
    
    // Handle tenant form submission
    $('#tenantForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'ajax_apartments.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing your request.'
                });
            }
        });
    });
    
    // Handle add new apartment button
    $('#addNewApartmentBtn').click(function() {
        $('#apartmentForm')[0].reset();
        $('#apartmentModalLabel').text('Add New Apartment');
        $('#edit_apartment_id').val('');
        $('#apartmentModal').modal('show');
    });
    
    // Handle edit apartment button
    $('.edit-apartment-btn').click(function() {
        const apartmentId = $(this).data('apartment-id');
        const apartmentNumber = $(this).data('apartment-number');
        const description = $(this).data('description');
        const categoryId = $(this).data('category-id');
        const price = $(this).data('price');
        
        $('#apartmentModalLabel').text('Edit Apartment');
        $('#edit_apartment_id').val(apartmentId);
        $('#apartment_number_input').val(apartmentNumber);
        $('#description').val(description);
        $('#category_id').val(categoryId);
        $('#price').val(price);
        
        $('#apartmentModal').modal('show');
    });
    
    // Handle apartment form submission
    $('#apartmentForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'ajax_apartments.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing your request.'
                });
            }
        });
    });
});
</script>