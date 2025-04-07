<?php include('db_connect.php'); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">

<head>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
</head>

<div class="container-fluid">

    <div class="col-lg-12 mt-5">
        <div class="row">

            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Apartments List</b>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Apartment Details</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $apartment = $conn->query("
                                    SELECT 
                                        apartments.number as apartment_no,
                                        apartments.description as description,
                                        apartments.price as price,
                                        apartments.owner_id as owner,
                                        apartments.tenant_id as tenant,
                                        categories.name as cname,
                                        CONCAT(owner.first_name, ' ', owner.last_name) as owner_name,
                                        CONCAT(tenant.first_name, ' ', tenant.last_name) as tenant_name,
                                        categories.id as category_id,
                                        apartments.id as id                                
                                    FROM apartments
                                    LEFT JOIN categories ON apartments.category_id = categories.id
                                    INNER JOIN users as owner ON apartments.owner_id = owner.id 
                                    LEFT JOIN users as tenant ON apartments.tenant_id = tenant.id                                   
                                    WHERE apartments.manager_id = {$_SESSION['login_id']}
                                    ");
                                while ($row = $apartment->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td class="align-middle">
                                            <div class="apartment-details">
                                                <!-- Apartment Number - Primary Info -->
                                                <div class="d-flex mb-2 align-items-center">
                                                    <span class="text-primary me-2"><i class="fas fa-home"></i> Apartment #: &nbsp;</span>
                                                    <span class="fw-bold text-dark"><?php echo $row['apartment_no'] ?></span>
                                                </div>

                                                <!-- Category - Secondary Info -->
                                                <div class="d-flex mb-2 align-items-center">
                                                    <span class="text-info me-2"><i class="fas fa-tag"></i> Category: &nbsp;</span>
                                                    <span class="text-muted"><?php echo $row['cname'] ?></span>
                                                </div>

                                                <!-- Description - Neutral Info -->
                                                <div class="d-flex mb-2 align-items-center">
                                                    <span class="text-secondary me-2"><i class="fas fa-align-left"></i> Description: &nbsp;</span>
                                                    <span class="text-dark"><?php echo $row['description'] ?></span>
                                                </div>

                                                <!-- Price - Financial Info (Attention) -->
                                                <div class="d-flex mb-2 align-items-center">
                                                    <span class="text-success me-2"><i class="fas fa-dollar-sign"></i> Price: &nbsp;</span>
                                                    <span class="fw-semibold text-success">Tsh <?php echo number_format($row['price'], 2) ?></span>
                                                </div>

                                                <!-- Owner - Important Relationship -->
                                                <div class="d-flex mb-2 align-items-center">
                                                    <span class="text-purple me-2"><i class="fas fa-user-tie"></i> Owner: &nbsp;</span>
                                                    <span class="text-dark fw-medium"><?php echo $row['owner_name']?></span>
                                                </div>

                                                <?php if (isset($row['tenant'])): ?>
                                                    <!-- Tenant - Current Occupant (Highlighted) -->
                                                    <div class="d-flex align-items-center">
                                                        <span class="text-secondary me-2"><i class="fas fa-user"></i> Tenant: &nbsp;</span>
                                                        <span class="text-dark fw-medium"><?php echo $row['tenant_name']?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge p-2 <?php echo (isset($row['tenant']) && !empty($row['tenant'])) ? 'bg-danger' : 'bg-success'; ?>">
                                                <?php echo (isset($row['tenant']) && !empty($row['tenant'])) ? 'Occupied' : 'Available'; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <?php if (isset($row['tenant']) && !empty($row['tenant'])): ?>
                                                <!-- When occupied - show both Update and Set Free buttons -->
                                                <button class="btn btn-sm btn-warning edit_apartment mb-1"
                                                    data-toggle="modal"
                                                    data-target="#tenantModal"
                                                    type="button"
                                                    data-apartment-id="<?php echo $row['id'] ?>"
                                                    data-tenant="<?php echo $row['tenant'] ?>">
                                                    <i class="fas fa-user-edit"></i> Upgrade
                                                </button>
                                                <button class="btn btn-sm btn-danger set-free"
                                                    data-apartment-id="<?php echo $row['id'] ?>">
                                                    <i class="fas fa-door-open"></i> Free
                                                </button>
                                            <?php else: ?>
                                                <!-- When available - show only Add Tenant button -->
                                                <button class="btn btn-sm btn-primary edit_apartment"
                                                    data-toggle="modal"
                                                    data-target="#tenantModal"
                                                    type="button"
                                                    data-apartment-id="<?php echo $row['id'] ?>">
                                                    <i class="fas fa-user-plus"></i> Assign
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->

            <!-- Tenant Modal -->
            <div class="modal fade" id="tenantModal" tabindex="-1" aria-labelledby="tenantModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form id="tenantForm">
                        <div class="modal-content">
                            <div class="modal-header bg-light ">
                                <h5 class="modal-title" id="tenantModalLabel">Assign Tenant</h5>
                                <button type="button" class="close " data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <label class="font-weight-bold">Select a Tenant</label>
                                            <input type="hidden" name="apartment_id" id="apartment_id">

                                            <select name="tenant" id="tenant" class="form-control select2" style="width: 100%;" required>
                                                <option value="" selected disabled>--Select Tenant-- </option>
                                                <?php
                                                $tenants = $conn->query("SELECT * FROM users WHERE type = 4");
                                                while ($tenant = $tenants->fetch_assoc()):
                                                ?>
                                                    <option value="<?php echo $tenant['id'] ?>">
                                                        <?php echo $tenant['first_name'] . " " . $tenant['last_name'] ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>

                                        <div class="text-center my-3">
                                            <span class="text-muted">OR</span>
                                        </div>

                                        <div class="text-center my-3">
                                            <a href="index.php?page=<?php echo base64_encode('views/manager/tenants'); ?>">
                                                <i class="fas fa-user-plus mr-2"></i>Create New Tenant
                                            </a>
                                        </div>
                                        
                                    </div>

                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="$('#tenant').val('').trigger('change');">
                                    <i class="fas fa-times mr-1"></i> Clear Selection
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                // $(document).ready(function() {
                //     // Initialize Select2
                //     $('.select2').select2({
                //         placeholder: "Search for a tenant...",
                //         allowClear: true,
                //         dropdownParent: $('#tenantModal')
                //     });
                // });
            </script>
        </div>
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

<script>
    new DataTable('#example', {
        layout: {
            topStart: {
                buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5']
            }
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle edit apartment clicks
        document.querySelectorAll('.edit_apartment').forEach(button => {
            button.addEventListener('click', function() {
                const a_id = this.getAttribute('data-apartment-id');
                const tenant = this.getAttribute('data-tenant');

                document.getElementById('apartment_id').value = a_id;
                document.getElementById('tenant').value = tenant || '';

                const modalTitle = document.getElementById('tenantModalLabel');
                if (modalTitle) {
                    modalTitle.textContent = tenant ? 'Update Tenant' : 'Assign a Tenant';
                }
            });
        });

        // Handle form submission
        const tenantForm = document.getElementById('tenantForm');
        if (tenantForm) {
            tenantForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const tenantSelect = document.getElementById('tenant');
                if (!tenantSelect.value) {
                    alert('Please select a tenant first!');
                    return;
                }

                const formData = new FormData(this);

                alert('Sending this data to server:\n' +
                    Array.from(formData.entries()).map(([key, val]) => `${key}: ${val}`).join('\n'));

                fetch('ajax.php?action=assign_tenant', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(response => {
                        console.log('Server responded with:\n' + response);
                        try {
                            const resp = JSON.parse(response);
                            if (resp.status === 'success') {
                                alert('Success: ' + resp.msg);
                                location.reload();
                            } else {
                                alert('Error: ' + (resp.msg || 'Unknown error'));
                            }
                        } catch (e) {
                            console.error('Raw server response:\n' + response);
                        }
                    })
                    .catch(error => {
                        alert('Request failed: ' + error.message);
                    });
            });
        }

        // Clear modal on close
        document.querySelector('#tenantModal .close').addEventListener('click', function() {
            document.getElementById('tenant').value = '';
            document.getElementById('apartment_id').value = '';
        });
    });
</script>

<script>
    // Add this to your existing JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Set Free button clicks
        document.querySelectorAll('.set-free').forEach(button => {
            button.addEventListener('click', function() {
                const apartmentId = this.getAttribute('data-apartment-id');

                if (confirm('Are you sure you want to set this apartment free?')) {
                    fetch('ajax.php?action=set_apartment_free', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'apartment_id=' + apartmentId
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                alert('Apartment has been set free!');
                                location.reload();
                            } else {
                                alert('Error: ' + data.msg);
                            }
                        })
                        .catch(error => {
                            alert('Network error: ' + error.message);
                        });
                }
            });
        });
    });
</script>