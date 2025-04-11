<?php include('db_connect.php'); ?>

<div class="container-fluid">

    <div class="col-lg-12">
        <div class="row mb-3 mt-3">

            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>List of Tenant</b>
                        <span class="float:right">
                            <a class="btn btn-primary btn-block btn-sm col-sm-2 float-right"
                                href="javascript:void(0)"
                                id="new_tenant"
                                data-toggle="modal"
                                data-target="#tenantModal">
                                <i class="fa fa-plus"></i> New Tenant
                            </a>
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="">Name</th>
                                    <th class="">Phone</th>
                                    <th class="">Apartment NO</th>
                                    <th class="">Monthly Rate</th>
                                    <th class="">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $tenant = $conn->query("
                                    SELECT DISTINCT
                                        CONCAT(tenant.first_name, ' ', tenant.last_name) as tenant_name,
                                        tenant.id as id,
                                        tenant.phone as phone,
                                        tenant.username as email,
                                        tenant.avatar as avatar,
                                        apt.number as apartment_no,
                                        apt.price as price
                                    FROM users as tenant                                
                                    LEFT JOIN contracts as contr ON tenant.id = contr.tenant_id
                                    LEFT JOIN apartments as apt ON apt.id = contr.apartment_id
                                    WHERE tenant.type = 4 AND (apt.manager_id = '{$_SESSION['login_id']}' OR created_by = '{$_SESSION['login_id']}')
                                    ORDER BY id DESC
                                ");
                                while ($row = $tenant->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($row['avatar'])): ?>
                                                    <img src="uploads/avatars/<?php echo $row['avatar'] ?>" class="rounded-circle me-2" width="40" height="40" alt="Tenant Avatar">
                                                <?php else: ?>
                                                    <div class="avatar-placeholder rounded-circle bg-light text-center me-2" style="width:40px;height:40px;line-height:40px;">
                                                        <i class="fas fa-user text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <p class="mb-0 fw-bold"><?php echo ucwords($row['tenant_name']) ?></p>
                                                    <small class="text-muted"><?php echo $row['email'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <p class="mb-0"><?php echo $row['phone'] ?></p>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-primary"><?php echo $row['apartment_no'] ?></span>
                                        </td>
                                        <td class="align-middle">
                                            <p class="mb-0"><b>Tsh <?php echo number_format($row['price'], 2) ?></b></p>
                                        </td>
                                        <td class="text-center align-middle">
                                            <button class="btn btn-sm btn-outline-primary view_payment" type="button" data-id="<?php echo $row['id'] ?>">
                                                <i class="fas fa-receipt"></i> Payments
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

    <!-- Tenant Modal -->
    <!-- Tenant Modal -->
    <div class="modal fade" id="tenantModal" tabindex="-1" aria-labelledby="tenantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="tenantForm" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tenantModalLabel">Add New Tenant</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="newTenantForm">
                                    <!-- Personal Information Card -->

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="tenant_fname" class="font-weight-bold">First Name <span class="text-danger">*</span></label>
                                                <input type="text" name="tenant_fname" id="tenant_fname" class="form-control" placeholder="Enter First Name" required>
                                                <div class="invalid-feedback">Please provide first name</div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="tenant_lname" class="font-weight-bold">Last Name <span class="text-danger">*</span></label>
                                                <input type="text" name="tenant_lname" id="tenant_lname" class="form-control" placeholder="Enter Last Name" required>
                                                <div class="invalid-feedback">Please provide last name</div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="tenant_phone" class="font-weight-bold">Phone Number <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-white"><i class="fas fa-phone text-primary"></i></span>
                                                    </div>
                                                    <input type="tel"
                                                        name="tenant_phone"
                                                        id="tenant_phone"
                                                        class="form-control"
                                                        placeholder="e.g. 0712345678"
                                                        inputmode="numeric"
                                                        maxlength="10"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"
                                                        required>
                                                    <div class="invalid-feedback">Valid phone number required</div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="gender" class="font-weight-bold">Gender<span class="text-danger">*</span></label>
                                                <select name="gender" id="gender" class="form-control">
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Account Information Card -->

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <div class="form-group">
                                                    <label for="tenant_email" class="font-weight-bold">Email Address <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-white"><i class="fas fa-envelope text-primary"></i></span>
                                                        </div>
                                                        <input type="email" name="tenant_email" id="tenant_email" class="form-control" placeholder="e.g. tenant@example.com" required>
                                                        <div class="invalid-feedback">Valid email address required</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="avatar" class="font-weight-bold">Tenat Image<span class="text-danger">*</span></label>
                                                    <div class="custom-file">
                                                        <input type="file" name="avatar" id="avatar" class="custom-file-input" accept="image/png, image/jpeg, image/jpg" required>
                                                        <label class="custom-file-label" for="avatar">Choose profile image</label>
                                                        <small class="form-text text-muted">Max size: 5MB (JPG, PNG)</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-center">
                                                <div class="avatar-preview-container  ">
                                                    <img id="avatarPreview" src="#" alt="Preview" class="d-none" style="width:120px;height:120px;object-fit:cover;">
                                                </div>
                                            </div>


                                        </div>

                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle mr-2"></i> A default password <strong>rental</strong> will be created for the tenant.
                                        </div>

                                        <!-- Profile Picture -->
                                        <div class="row align-items-center">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Save Tenant
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Avatar preview functionality
            const avatarInput = document.getElementById('avatar');
            const avatarPreview = document.getElementById('avatarPreview');
            const fileLabel = document.querySelector('.custom-file-label');

            if (avatarInput) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];

                    if (file) {
                        // Check file size (5MB limit)
                        if (file.size > 5 * 1024 * 1024) {
                            alert('File size exceeds 5MB limit');
                            this.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            avatarPreview.src = e.target.result;
                            avatarPreview.classList.remove('d-none');
                            fileLabel.textContent = file.name;
                        }
                        reader.readAsDataURL(file);
                    } else {
                        avatarPreview.src = '#';
                        avatarPreview.classList.add('d-none');
                        fileLabel.textContent = 'Choose profile image';
                    }
                });
            }

            // Form validation and submission
            const tenantForm = document.getElementById('tenantForm');

            if (tenantForm) {
                tenantForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Reset validation states
                    const requiredInputs = this.querySelectorAll('[required]');
                    let isValid = true;

                    requiredInputs.forEach(input => {
                        input.classList.remove('is-invalid');

                        if (!input.value.trim()) {
                            input.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            // Special validation for email
                            if (input.id === 'tenant_email' && !isValidEmail(input.value)) {
                                input.classList.add('is-invalid');
                                const feedback = input.nextElementSibling ||
                                    input.parentElement.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = 'Please enter a valid email address';
                                }
                                isValid = false;
                            }

                            // Special validation for phone
                            if (input.id === 'tenant_phone' && !isValidPhone(input.value)) {
                                input.classList.add('is-invalid');
                                const feedback = input.nextElementSibling ||
                                    input.parentElement.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = 'Please enter a valid phone number';
                                }
                                isValid = false;
                            }
                        }
                    });

                    if (isValid) {
                        // Submit form via Fetch API
                        const formData = new FormData(this);

                        fetch('ajax.php?action=create_tenant', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.text();
                            })
                            .then(text => {
                                try {
                                    const data = JSON.parse(text);
                                    if (data.status === 'success') {
                                        alert(data.msg);
                                        // location.reload();
                                    } else {
                                        throw new Error(data.msg || 'Operation failed');
                                    }
                                } catch {
                                    // If response isn't JSON, show raw response
                                    alert(text || 'Tenant created successfully');
                                    // location.reload();
                                }
                            })
                            .catch(error => {
                                alert('Error: ' + error.message);
                            });
                    }
                });
            }

            // Helper functions
            function isValidEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            function isValidPhone(phone) {
                return /^[0-9]{10,15}$/.test(phone);
            }
        });
    </script>

    <style>
        .avatar-preview-container {
            position: relative;
            margin: 0 auto;
        }

        .custom-file-label::after {
            content: "Browse";
        }

        .invalid-feedback {
            display: none;
            color: #dc3545;
        }

        .is-invalid~.invalid-feedback,
        .is-invalid~div>.invalid-feedback {
            display: block;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Search for a tenant...",
                allowClear: true,
                dropdownParent: $('#tenantModal')
            });
        });
    </script>


</div>

<style>
    td {
        vertical-align: middle !important;
    }

    td p {
        margin: unset
    }

    img {
        max-width: 100px;
        max-height: 150px;
    }
</style>