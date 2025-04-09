<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row">

            <div class="col-md-12 p-0">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-receipt mr-2"></i>Payment Records</h5>

                        <!-- Wrap the right side controls -->
                        <div class="d-flex align-items-center">
                            <!-- Search -->
                            <div class="mr-2" style="max-width: 250px;">
                                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search payments...">
                            </div>
                            <!-- Button -->
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#paymentModal" id="new_payment">
                                <i class="fas fa-plus mr-1"></i> New Payment
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="paymentTable" class="table table-hover table-striped mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" data-sort="index">#</th>
                                        <th data-sort="tenant">Tenant</th>
                                        <th data-sort="house">House #</th>
                                        <th data-sort="period">Period</th>
                                        <th data-sort="payment_date">Payment Date</th>
                                        <th data-sort="method">Method</th>
                                        <th data-sort="status">Status</th>
                                        <th class="text-right" data-sort="amount">Amount (Tsh)</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $payments = $conn->query("
                                        SELECT 
                                        CONCAT(tenant.first_name, ' ', tenant.last_name) as name,
                                        apt.number AS apartment_no,
                                        p.amount,
                                        p.from_date as from_date,
                                        p.to_date as to_date,
                                        p.id,
                                        p.payment_date,
                                        p.payment_method,
                                        p.status,
                                        p.verified_by,
                                        p.invoice
                                        FROM users as tenant
                                        INNER JOIN payments as p ON tenant.id = p.tenant_id
                                        INNER JOIN apartments as apt ON apt.tenant_id = tenant.id
                                        WHERE tenant.type = 4 AND apt.manager_id = '{$_SESSION['login_id']}'
                                        ORDER BY p.id DESC
                                    ");
                                    while ($row = $payments->fetch_assoc()):
                                        $period = date('M d, Y', strtotime($row['from_date'])) . ' - ' . date('M d, Y', strtotime($row['to_date']));
                                        $paymentDate = $row['payment_date'] ? date('M d, Y', strtotime($row['payment_date'])) : 'N/A';
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo $i++ ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <h6 class="mb-0"><?php echo ucwords($row['name']) ?></h6>
                                                        <!-- <small class="text-muted">Invoice: <?php echo ''//$row['invoice'] ?></small> -->
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-light"><?php echo $row['apartment_no'] ?></span>
                                            </td>
                                            <td>
                                                <small><?php echo $period ?></small>
                                            </td>
                                            <td>
                                                <small><?php echo $paymentDate ?></small>
                                            </td>
                                            <td>
                                                <?php
                                                $method_badge = [
                                                    'credit_card' => 'warning',
                                                    'mobile_money' => 'secondary',
                                                    'bank_transfer' => 'info',
                                                    'cash' => 'success'
                                                ];
                                                ?>
                                                <span class="badge badge-<?php echo $method_badge[$row['payment_method']] ?? 'secondary' ?>">
                                                    <?php echo ucwords(str_replace('_', ' ', $row['payment_method'])) ?>
                                                </span>
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
                                            <td class="text-right  text-primary">
                                                <small><?php echo number_format($row['amount'], 2) ?></small>
                                            </td>

                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary view_payment" data-id="<?php echo $row['id'] ?>"
                                                        title="View Details">
                                                        <i class="fas fa-pencil-alt"></i>
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

            <style>
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

            <script>
            </script>

            <!-- Payment Modal -->
            <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title" id="paymentModalLabel">New Payment Entry</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="paymentForm">
                            <div class="modal-body">
                                <!-- Tenant & Apartment -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tenant_id" class="control-label">Tenant</label>
                                            <select class="form-control select2" name="tenant_id" id="tenant_id" required>
                                                <option value="" selected disabled>-- Select Tenant --</option>
                                                <?php
                                                $tenants = $conn->query("
                                                    SELECT u.id, CONCAT(u.first_name,' ',u.last_name) as name, a.number 
                                                    FROM users u 
                                                    INNER JOIN apartments a ON a.tenant_id = u.id 
                                                    WHERE u.type = 4 AND a.manager_id = '{$_SESSION['login_id']}'
                                                ");
                                                while ($row = $tenants->fetch_assoc()):
                                                ?>
                                                    <option value="<?php echo $row['id'] ?>" data-apartment="<?php echo $row['number'] ?>">
                                                        <?php echo ucwords($row['name']) . ' - Apt #' . $row['number'] ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="apartment_no" class="control-label">Apartment #</label>
                                            <input type="text" class="form-control" id="apartment_no" readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- Amount & Payment Method -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount" class="control-label">Amount (Tsh)</label>
                                            <input
                                                type="text"
                                                inputmode="numeric"
                                                class="form-control"
                                                name="amount"
                                                id="amount"
                                                min="0"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_method" class="control-label">Payment Method</label>
                                            <select class="form-control" name="payment_method" id="payment_method" required>
                                                <option value="" disabled selected>-- Select Method --</option>
                                                <option value="cash">Cash</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="credit_card">Credit Card</option>
                                                <option value="mobile_money">Mobile Money</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dates -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_date" class="control-label">Payment Date</label>
                                            <input type="date" class="form-control" name="payment_date" id="payment_date" value="<?php echo date('Y-m-d') ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="from_date" class="control-label">From Date</label>
                                            <input type="date" class="form-control" name="from_date" id="from_date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="to_date" class="control-label">To Date</label>
                                            <input type="date" class="form-control" name="to_date" id="to_date" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Payment Modal -->

        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update apartment number when tenant is selected
        const tenantSelect = document.getElementById('tenant_id');
        const apartmentInput = document.getElementById('apartment_no');

        if (tenantSelect && apartmentInput) {
            tenantSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                apartmentInput.value = selectedOption.dataset.apartment || '';
            });
        }

        // Handle form submission
        const paymentForm = document.getElementById('paymentForm');
        if (paymentForm) {
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                const submitButton = this.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.textContent;
                submitButton.disabled = true;
                submitButton.textContent = 'Processing...';

                // Collect form data
                const formData = new FormData(this);

                // Send AJAX request
                fetch('ajax.php?action=save_payment', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        try {
                            console.log(data);
                            data = JSON.parse(data);
                            if (data.status === 'success') {
                                alert('Payment saved successfully!');
                                // Optionally, refresh the page or update the table
                                location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        } catch (e) {
                            console.error('Error parsing JSON:', e);
                            alert('An error occurred while processing the response');
                            return;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while saving the payment');
                    })
                    .finally(() => {
                        // Restore button state
                        submitButton.disabled = false;
                        submitButton.textContent = originalButtonText;
                    });
            });
        }

        // Handle view payment buttons
        document.querySelectorAll('.view_payment').forEach(button => {
            button.addEventListener('click', function() {
                const paymentId = this.dataset.id;
                // Implement your view modal logic here
                console.log('View payment:', paymentId);
            });
        });

        // Amount input validation
        const amountInput = document.getElementById('amount');
        if (amountInput) {
            amountInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '');

                // Ensure only one decimal point
                if ((this.value.match(/\./g) || []).length > 1) {
                    this.value = this.value.substring(0, this.value.lastIndexOf('.'));
                }
            });
        }
    });
</script>

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