<?php include('db_connect.php'); ?>

<div class="container-fluid">

    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12">

            </div>
        </div>
        <div class="row">

            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>List of Payments</b>
                        <span class="float:right">
                            <a
                                class="btn btn-primary btn-block btn-sm col-sm-2 float-right"
                                href="javascript:void(0)"
                                data-toggle="modal"
                                data-target="#paymentModal"
                                id="new_payment">
                                <i class="fa fa-plus"></i> New Entry
                            </a>
                        </span>
                    </div>
                    <div class="card-body">
                        <table id="example" class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="">Tenant</th>
                                    <th class="">House #</th>
                                    <th class="">Amount</th>
                                    <th class="">From Date</th>
                                    <th class="">Upto Date</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
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
                                    p.verified_by
                                    FROM users as tenant
                                    INNER JOIN payments as p ON tenant.id = p.tenant_id
                                    INNER JOIN apartments as apt ON apt.tenant_id = tenant.id
                                ");
                                while ($row = $payments->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td class="">
                                            <p><b><?php echo ucwords($row['name']) ?></b></p>
                                        </td>
                                        <td class="">
                                            <p><b><?php echo $row['apartment_no'] ?></b></p>
                                        </td>
                                        <td class="text-right">
                                            <p><b><?php echo number_format($row['amount'], 2) ?></b></p>
                                        </td>
                                        <td class="">
                                            <p><b><?php echo date('M d, Y', strtotime($row['from_date'])) ?></b></p>
                                        </td>
                                        <td class="">
                                            <p><b><?php echo date('M d, Y', strtotime($row['to_date'])) ?></b></p>
                                        </td>
                                        <td>
                                            <p><b><?php echo $row['verified_by'] == 1 ? '<div class="badge badge-success p-2">Verified</div>' : '<div class="badge badge-secondary p-2">Pending</div>' ?></b></p>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary view_payment" type="button" data-id="<?php echo $row['id'] ?>">View</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Table Panel -->


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
                                            <label for="payment_date" class="control-label">Payment Date</label>
                                            <input type="date" class="form-control" name="payment_date" id="payment_date" value="<?php echo date('Y-m-d') ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="from_date" class="control-label">From Date</label>
                                            <input type="date" class="form-control" name="from_date" id="from_date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="to_date" class="control-label">To Date</label>
                                            <input type="date" class="form-control" name="to_date" id="to_date" required>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="remarks" class="control-label">Remarks</label>
                                    <textarea class="form-control" name="remarks" id="remarks" rows="2"></textarea>
                                </div> -->
                            </div>
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