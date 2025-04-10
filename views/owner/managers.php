<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row mb-3 mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>List of Managers</b>
                        <span class="float:right">
                        <a class="btn btn-primary btn-sm float-right" href="javascript:void(0)" data-toggle="modal" data-target="#tModal">
                            <i class="fa fa-plus"></i> New Manager
                        </a>

                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="managersTableBody">
                                <!-- Managers will be inserted here dynamically -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Manager Modal -->
    <div class="modal fade" id="tModal" tabindex="-1" aria-labelledby="managerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="managerForm" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="managerModalLabel">Add New manager</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="newmanagerForm">
                                    <!-- Personal Information Card -->

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="manager_fname" class="font-weight-bold">First Name <span class="text-danger">*</span></label>
                                                <input type="text" name="manager_fname" id="manager_fname" class="form-control" placeholder="Enter First Name" required>
                                                <div class="invalid-feedback">Please provide first name</div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="manager_lname" class="font-weight-bold">Last Name <span class="text-danger">*</span></label>
                                                <input type="text" name="manager_lname" id="manager_lname" class="form-control" placeholder="Enter Last Name" required>
                                                <div class="invalid-feedback">Please provide last name</div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="manager_phone" class="font-weight-bold">Phone Number <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-white"><i class="fas fa-phone text-primary"></i></span>
                                                    </div>
                                                    <input type="tel"
                                                        name="manager_phone"
                                                        id="manager_phone"
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
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Account Information Card -->

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <div class="form-group">
                                                    <label for="manager_email" class="font-weight-bold">Email Address <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-white"><i class="fas fa-envelope text-primary"></i></span>
                                                        </div>
                                                        <input type="email" name="manager_email" id="manager_email" class="form-control" placeholder="e.g. manager@example.com" required>
                                                        <div class="invalid-feedback">Valid email address required</div>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                        </div>

                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle mr-2"></i> A default password <strong>rental</strong> will be created for the manager.
                                        </div>

                                        <!-- Profile Picture -->
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="avatar" id="avatar" class="custom-file-input" accept="image/png, image/jpeg, image/jpg">
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Save manager
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>

<!-- Manager Details Modal -->
<div class="modal fade" id="managerDetailsModal" tabindex="-1" aria-labelledby="managerDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="managerDetailsLabel">Manager Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="managerDetailsContent">
                    <!-- Manager Details will be inserted here dynamically -->
                    <div class="form-group">
                        <label><strong>Name:</strong></label>
                        <p id="managerName"></p>
                    </div>
                    <div class="form-group">
                        <label><strong>Email:</strong></label>
                        <p id="managerEmail"></p>
                    </div>
                    <div class="form-group">
                        <label><strong>Phone:</strong></label>
                        <p id="managerPhone"></p>
                    </div>
                    <hr>
                    <h5>Assigned Apartments</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Apartment No.</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="apartmentsTableBody">
                            <!-- Apartments will be inserted here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Close
                </button>
            </div>
        </div>
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
            const managerForm = document.getElementById('managerForm');

            if (managerForm) {
                managerForm.addEventListener('submit', function(e) {
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
                            if (input.id === 'manager_email' && !isValidEmail(input.value)) {
                                input.classList.add('is-invalid');
                                const feedback = input.nextElementSibling ||
                                    input.parentElement.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = 'Please enter a valid email address';
                                }
                                isValid = false;
                            }

                            // Special validation for phone
                            if (input.id === 'manager_phone' && !isValidPhone(input.value)) {
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

                        fetch('ajax.php?action=create_manager', {
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
                                        location.reload();
                                    } else {
                                        throw new Error(data.msg || 'Operation failed');
                                    }
                                } catch {
                                    // If response isn't JSON, show raw response
                                    alert(text || 'manager created successfully');
                                    location.reload();
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

    <script>
                $(document).ready(function() {
            // Function to load managers
            function loadManagers() {
                $.ajax({
                    url: 'ajax.php',
                    method: 'GET',
                    data: { action: 'get_managers' },
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.status !== 'error') {
                            var managers = response.data;
                            var tableBody = $('#managersTableBody');
                            tableBody.empty(); // Clear the current table content

                            // Loop through the managers and append rows to the table
                            $.each(managers, function(index, manager) {
                                var row = '<tr>' +
                                            '<td class="text-center">' + (index + 1) + '</td>' +
                                            '<td>' + manager.name + '</td>' +
                                            '<td>' + manager.phone + '</td>' +
                                            '<td>' + manager.email + '</td>' +
                                            '<td class="text-center">' +
                                                '<button class="btn btn-sm btn-outline-info edit_manager" data-id="' + manager.id + '"><i class="fas fa-edit"></i></button>' +
                                                '<button class="btn btn-sm btn-outline-danger delete_manager" data-id="' + manager.id + '"><i class="fas fa-trash"></i></button>' +
                                                '<button class="btn btn-sm btn-outline-success view_manager_details" data-id="' + manager.id + '"><i class="fas fa-eye"></i> View Details</button>'
                                            '</td>' +
                                        '</tr>';
                                tableBody.append(row);
                            });
                        }
                    },
                    error: function() {
                        alert('Error loading managers');
                    }
                });
            }

            // Load managers when the page is ready
            loadManagers();
        });


        $(document).ready(function() {
    // View manager details
            $(document).on('click', '.view_manager_details', function() {
                const managerId = $(this).data('id');
                
                // Fetch manager details via AJAX
                $.ajax({
                    url: 'ajax.php',
                    method: 'GET',
                    data: { action: 'get_manager_details', manager_id: managerId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status !== 'error') {
                            const manager = response.data.manager;
                            const apartments = response.data.apartments;
                            
                            // Populate manager details
                            $('#managerName').text(manager.first_name + ' ' + manager.last_name);
                            $('#managerEmail').text(manager.email);
                            $('#managerPhone').text(manager.phone);

                            // Populate apartments table
                            const apartmentsTableBody = $('#apartmentsTableBody');
                            apartmentsTableBody.empty();
                            if (apartments.length > 0) {
                                apartments.forEach(function(apartment, index) {
                                    const row = '<tr>' +
                                                    '<td>' + (index + 1) + '</td>' +
                                                    '<td>' + apartment.number + '</td>' +
                                                    '<td>' + apartment.price + '</td>' +
                                                    '<td><button class="btn btn-sm btn-outline-danger remove_apartment" data-apartment-id="' + apartment.id + '" data-manager-id="' + managerId + '"><i class="fas fa-trash"></i> Remove</button></td>' +

                                                '</tr>';
                                    apartmentsTableBody.append(row);
                                });
                            } else {
                                apartmentsTableBody.append('<tr><td colspan="3">No apartments assigned.</td></tr>');
                            }

                            // Show the modal
                            $('#managerDetailsModal').modal('show');
                        } else {
                            alert('Error fetching manager details');
                        }
                    },
                    error: function() {
                        alert('Error loading manager details');
                    }
                });
            });
        });
        $(document).ready(function() {
            // Handle the click on the "Remove" button for apartment
            $(document).on('click', '.remove_apartment', function() {
                const apartmentId = $(this).data('apartment-id');
                const managerId = $(this).data('manager-id');
                
                // Ask for confirmation before removing the apartment
                if (confirm('Are you sure you want to remove this apartment from the manager?')) {
                    // Send an AJAX request to remove the apartment
                    $.ajax({
                        url: 'ajax.php',
                        method: 'POST',
                        data: {
                            action: 'remove_apartment',
                            apartment_id: apartmentId,
                            manager_id: managerId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                // If successful, remove the apartment row from the table
                                alert('Apartment removed successfully!');
                                // Remove the row from the table (this can be done dynamically)
                                $('[data-apartment-id="' + apartmentId + '"]').closest('tr').remove();
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function() {
                            alert('Error removing apartment');
                        }
                    });
                }
            });
        });


    </script>