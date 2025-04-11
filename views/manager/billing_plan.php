<!-- <head>
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

        .avatar-sm {
            width: 32px;
            height: 32px;
            line-height: 32px;
            text-align: center;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            vertical-align: middle;
        }

        .badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .05);
        }

        .payment-table {
            font-size: 0.9rem;
        }
    </style>

</head> -->

<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-12 p-0">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-alt mr-2"></i>Contracts Records</h5>

                    <!-- Wrap the right side controls -->
                    <div class="d-flex align-items-center">
                        <!-- Search -->
                        <div class="mr-2" style="max-width: 250px;">
                            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search contracts...">
                        </div>
                        <!-- Button -->
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newContractModal" id="new_payment">
                            <i class="fas fa-plus mr-1"></i> New Contract
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="paymentTable" class="table table-hover table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center" data-sort="index">#</th>
                                    <th data-sort="tenant">Contract #</th>
                                    <th data-sort="house">Tenant</th>
                                    <th data-sort="period">Apartment</th>
                                    <th data-sort="payment_date">Start Date</th>
                                    <th data-sort="method">End Date</th>
                                    <th class="text-right">Amount required</th>
                                    <th class="text-right" data-sort="amount">Amount Paid</th>
                                    <th data-sort="status">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $payments = $conn->query("
                                        SELECT 
                                            CONCAT(tenant.first_name, ' ', tenant.last_name) AS name,
                                            apt.number AS apartment_no,
                                            p.amount,
                                            p.id,
                                            p.payment_date,
                                            p.payment_method,
                                            p.status,
                                            p.verified_by,
                                            p.invoice,
                                            contract.from_date,
                                            contract.id AS contract_id
                                        FROM users AS tenant
                                        INNER JOIN payments AS p ON tenant.id = p.tenant_id
                                        INNER JOIN contracts AS contract ON contract.id = p.contract_id
                                        INNER JOIN apartments AS apt ON apt.id = contract.apartment_id
                                        WHERE tenant.type = 4 AND apt.manager_id = '{$_SESSION['login_id']}'
                                        ORDER BY p.id DESC;
                                    ");
                                while ($row = $payments->fetch_assoc()):
                                    $contract_code = 'CT-' . $row['contract_id'] . '-' . date('Ym', strtotime($row['from_date']));
                                    $paymentDate = $row['payment_date'] ? date('M d, Y', strtotime($row['payment_date'])) : 'N/A';
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>

                                        <td>
                                            <small><?php echo $contract_code ?></small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0"><?php echo ucwords($row['name']) ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light"><?php echo $row['apartment_no'] ?></span>
                                        </td>

                                        <td>
                                            <small><?php echo $paymentDate ?></small>
                                        </td>
                                        <td>
                                            <small><?php echo $paymentDate ?></small>
                                        </td>
                                        <td class="text-right  text-primary">
                                            <small><?php echo number_format($row['amount'], 2) ?></small>
                                        </td>
                                        <td class="text-right  text-primary">
                                            <small><?php echo number_format($row['amount'], 2) ?></small>
                                        </td>
                                        <td>
                                            <?php
                                            $status_badge = [
                                                'pending' => 'secondary',
                                                'completed' => 'success',
                                                'failed' => 'danger',
                                                'verified' => 'primary'
                                            ];
                                            ?>
                                            <span class="badge badge-<?php echo $status_badge[$row['status']] ?>">
                                                <?php echo ucfirst($row['status']) ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-primary view_payment" data-id="<?php echo $row['id'] ?>"
                                                    title="View Details">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                <button class="btn btn-success view_payment" data-id="<?php echo $row['id'] ?>"
                                                    title="View Details">
                                                    <i class="fas fa-info"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted" id="recordInfo">
                            <!-- Record info like “Showing 1 to 10 of 50 payments” will go here -->
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0" id="customPagination">
                                <!-- Pagination buttons generated by JS -->
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- End of Main Content -->

    <!-- New Contract Modal -->
    <div class="modal fade" id="newContractModal" tabindex="-1" aria-labelledby="newContractModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title" id="newContractModalLabel">
                        <i class="fas fa-file-contract me-2 text-primary"></i> New Contract
                    </h5>
                    <span aria-hidden="true" type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</span>
                </div>
                <div class="modal-body">
                    <form id="contractForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tenantSelect" class="form-label fw-bold">Tenant</label>
                                <select class="form-control" id="tenantSelect" name="tenant_id" required>
                                    <option value="" selected disabled>-- Select Tenant --</option>
                                    <?php
                                    $tenants = $conn->query("
                                        SELECT id, CONCAT(first_name, ' ', last_name) AS name 
                                        FROM users WHERE type = 4 
                                        ORDER BY first_name ASC
                                    ");
                                    while ($tenant = $tenants->fetch_assoc()):
                                    ?>
                                        <option value="<?php echo $tenant['id'] ?>">
                                            <?php echo ucwords($tenant['name']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="propertySelect" class="form-label fw-bold">Property</label>
                                <select class="form-control" id="propertySelect" required name="apartment_id">
                                    <option value="" selected disabled>-- Select Property --</option>
                                    <?php
                                    $apartments = $conn->query("
                                        SELECT 
                                            apt.id AS apartment_id,
                                            apt.number AS apartment_no, 
                                            CONCAT(first_name, ' ', last_name) AS owner_name 
                                        FROM apartments as apt
                                        INNER JOIN users AS owner ON owner.id = apt.owner_id 
                                        WHERE apt.manager_id = '{$_SESSION['login_id']}'
                                        ORDER BY first_name ASC
                                    ");
                                    while ($row = $apartments->fetch_assoc()):
                                    ?>
                                        <option value="<?php echo $row['apartment_id']; ?>">
                                            <?php echo ucwords($row['apartment_no']) . " - " . ucwords($row['owner_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="startDate" class="form-label fw-bold">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="from_date" required>
                            </div>

                            <div class="col-md-4">
                                <label for="months" class="form-label fw-bold">Months</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="months"
                                    placeholder="e.g., 12"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 2); calculateEndDate();"
                                    name="months"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label for="endDate" class="form-label fw-bold">End Date</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="endDate"
                                    name="to_date"
                                    readonly
                                    required>
                            </div>

                            <!-- This script calculates the end date based on the start date and number of months -->
                            <script>
                                function calculateEndDate() {
                                    const startDateInput = document.getElementById('startDate');
                                    const monthsInput = document.getElementById('months');
                                    const endDateInput = document.getElementById('endDate');

                                    const startDateValue = startDateInput.value;
                                    const monthsValue = parseInt(monthsInput.value);

                                    if (startDateValue && monthsValue) {
                                        const startDate = new Date(startDateValue);
                                        startDate.setMonth(startDate.getMonth() + monthsValue);

                                        const year = startDate.getFullYear();
                                        const month = String(startDate.getMonth() + 1).padStart(2, '0');
                                        const day = String(startDate.getDate()).padStart(2, '0');

                                        endDateInput.value = `${year}-${month}-${day}`;
                                    } else {
                                        endDateInput.value = '';
                                    }
                                }

                                // Call on date change too
                                document.getElementById('startDate').addEventListener('change', calculateEndDate);
                            </script>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contractFile" class="form-label">Contract Document <span class="text-danger"> (PDF)</span></label>
                                    <div class="custom-file">
                                        <input type="file" name="contract_file" id="contractFile" class="custom-file-input" accept="image/png, image/jpeg, image/jpg">
                                        <label class="custom-file-label" for="avatar">Choose a document</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="contractType" class="form-label fw-bold">Lease Type</label>
                                <select class="form-control" id="contractType" required name="lease_type">
                                    <option value="" selected disabled>-- Select Lease Type --</option>
                                    <option value="resident">Resident</option>
                                    <option value="bussiness">Bussiness </option>
                                </select>
                            </div>

                            <div class="col-md-4 d-flex align-items-center">
                                <div class="form-check mt-3">
                                    <input type="checkbox" class="form-check-input" id="hasFamily" name="has_family" value="yes">
                                    <label class="form-check-label" for="hasFamily">Has Family?</label>
                                </div>
                            </div>

                        </div>

                        <div class="border-top p-3 bg-light">
                            <div class="text-primary pb-3">Witness Details</div>

                            <div class="row mb-3">

                                <div class="col-md-4">
                                    <label for="witnessFname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="witnessFname" placeholder="Witness First Name" name="witness_fname">
                                </div>
                                <div class="col-md-4">
                                    <label for="witnessLname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="witnessLname" placeholder="Witness Last Name" name="witness_lname">
                                </div>
                                <div class="col-md-4">
                                    <label for="witnessPhone" class="form-label">Witness Phone</label>
                                    <input
                                        type="tel"
                                        class="form-control"
                                        id="witnessPhone"
                                        placeholder="e.g., 0722334455"
                                        name="witness_phone"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                </div>

                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveContractBtn">
                        <i class="fas fa-save me-1"></i> Save Contract
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle the modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form submission handling
            document.getElementById('saveContractBtn').addEventListener('click', function() {
                const form = document.getElementById('contractForm');
                if (form.checkValidity()) {
                    // Here you would handle the form submission (AJAX call to your backend)
                    alert('Contract saved successfully!');
                    // Close the modal
                    var modal = bootstrap.Modal.getInstance(document.getElementById('newContractModal'));
                    modal.hide();
                } else {
                    form.reportValidity();
                }
            });
        });
    </script>

    <!-- JavaScript for table sorting, filtering, and pagination -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.querySelector('#paymentTable');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const searchInput = document.getElementById('searchInput');
            const paginationContainer = document.getElementById('customPagination');
            const recordInfo = document.getElementById('recordInfo');

            const rowsPerPage = 10;
            let currentPage = 1;
            let filteredRows = [...rows];
            let currentSort = {
                column: null,
                direction: 'asc'
            };

            function renderTable() {
                tbody.innerHTML = '';
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                const paginatedRows = filteredRows.slice(start, end);
                paginatedRows.forEach(row => tbody.appendChild(row));

                const total = filteredRows.length;
                const from = total === 0 ? 0 : start + 1;
                const to = Math.min(end, total);
                recordInfo.innerHTML = `Showing <strong>${from} to ${to}</strong> of <strong>${total}</strong> payments`;

                renderPagination(total);
            }

            function renderPagination(total) {
                paginationContainer.innerHTML = '';
                const totalPages = Math.ceil(total / rowsPerPage);

                // Previous button
                const prev = document.createElement('li');
                prev.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
                prev.innerHTML = `<a class="page-link" href="#">Previous</a>`;
                prev.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (currentPage > 1) {
                        currentPage--;
                        renderTable();
                    }
                });
                paginationContainer.appendChild(prev);

                // Page numbers
                for (let i = 1; i <= totalPages; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                    li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                    li.addEventListener('click', (e) => {
                        e.preventDefault();
                        currentPage = i;
                        renderTable();
                    });
                    paginationContainer.appendChild(li);
                }

                // Next button
                const next = document.createElement('li');
                next.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
                next.innerHTML = `<a class="page-link" href="#">Next</a>`;
                next.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (currentPage < totalPages) {
                        currentPage++;
                        renderTable();
                    }
                });
                paginationContainer.appendChild(next);
            }

            function filterRows() {
                const term = searchInput.value.toLowerCase();
                filteredRows = rows.filter(row =>
                    row.textContent.toLowerCase().includes(term)
                );
                currentPage = 1;
                renderTable();
            }

            function sortTable(columnIndex, type = 'string') {
                const direction = currentSort.direction === 'asc' ? 1 : -1;
                filteredRows.sort((a, b) => {
                    const aText = a.children[columnIndex].innerText.trim();
                    const bText = b.children[columnIndex].innerText.trim();

                    if (type === 'number') {
                        return (parseFloat(aText.replace(/,/g, '')) - parseFloat(bText.replace(/,/g, ''))) * direction;
                    } else {
                        return aText.localeCompare(bText) * direction;
                    }
                });

                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
                currentSort.column = columnIndex;
                renderTable();
            }

            // Bind sorting to th elements
            table.querySelectorAll('thead th').forEach((th, index) => {
                if (th.dataset.sort) {
                    th.style.cursor = 'pointer';
                    th.addEventListener('click', () => {
                        const isAmount = th.dataset.sort === 'amount';
                        sortTable(index, isAmount ? 'number' : 'string');
                    });
                }
            });

            searchInput.addEventListener('input', filterRows);
            renderTable();
        });
    </script>

    <!-- JavaScript for handling the new payment modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle save button click
            const saveContractBtn = document.getElementById('saveContractBtn');
            if (saveContractBtn) {
                saveContractBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Validate form
                    const form = document.getElementById('contractForm');
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    // Show loading state
                    const originalButtonText = saveContractBtn.innerHTML;
                    saveContractBtn.disabled = true;
                    saveContractBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';

                    // Collect form data including files
                    const formData = new FormData(form);

                    // Send AJAX request
                    fetch('ajax.php?action=save_contract', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            console.log("Raw response", response.text());
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json().catch(error => {
                                console.error("JSON parsing error:", error);
                                throw new Error("Invalid JSON response from server");
                            });
                        })
                        .then(data => {
                            if (data && data.status === 'success') {
                                alert_toast('Contract saved successfully!', 'success');
                                $('#newContractModal').modal('hide');
                                form.reset();
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                const errorMsg = data?.message || 'Unknown error occurred';
                                alert_toast('Error: ' + errorMsg, 'error');
                            }
                        }).catch(error => {
                            console.error('Error:', error);
                            alert_toast('An error occurred while saving the contract', 'error');
                        })
                        .finally(() => {
                            // Restore button state
                            saveContractBtn.disabled = false;
                            saveContractBtn.innerHTML = originalButtonText;
                        });
                });
            }
        });
    </script>

</div>