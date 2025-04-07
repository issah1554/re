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
                            id="new_payment" >
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
                                    p.id
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
            <!-- Payment Modal -->
            <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="paymentModalLabel">New Payment Entry</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
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
                                            <input type="number" class="form-control" name="amount" id="amount" step="0.01" min="0" required>
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
                                <div class="form-group">
                                    <label for="remarks" class="control-label">Remarks</label>
                                    <textarea class="form-control" name="remarks" id="remarks" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>

</div>
<script>
    $(document).ready(function() {
        // Initialize DataTable with export buttons
        new DataTable('#example', {
            layout: {
                topStart: {
                    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5']
                }
            }
        });

        // Show apartment number when tenant is selected
        $('#tenant_id').change(function() {
            var apartment = $(this).find(':selected').data('apartment');
            $('#apartment_no').val(apartment);
        });

        // Set default date ranges
        $('#from_date').val(new Date().toISOString().slice(0, 10));
        var nextMonth = new Date();
        nextMonth.setMonth(nextMonth.getMonth() + 1);
        $('#to_date').val(nextMonth.toISOString().slice(0, 10));

        // New Payment button click handler
        $('#new_payment').click(function() {
            $('#paymentForm')[0].reset();
            $('#apartment_no').val('');
            $('#paymentModal').modal('show');
        });

        // Form submission handler
        $('#paymentForm').submit(function(e) {
            e.preventDefault();
            start_loader();

            $.ajax({
                url: 'ajax.php?action=save_payment',
                method: 'POST',
                data: $(this).serialize(),
                error: err => {
                    console.log(err);
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast("Payment successfully saved", 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        alert_toast("Error: " + resp, 'error');
                        end_loader();
                    }
                }
            });
        });

        // View Payment button handler
        $('.view_payment').click(function() {
            var payment_id = $(this).attr('data-id');
            // You can implement a view modal here if needed
            alert("Viewing payment ID: " + payment_id);
        });
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