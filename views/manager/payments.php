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
                        <span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="javascript:void(0)" id="new_payment">
                                <i class="fa fa-plus"></i> New Entry
                            </a></span>
                    </div>
                    <div class="card-body">
                        <table class="table table-condensed table-bordered table-hover">
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
            <!-- Table Panel -->
        </div>
    </div>

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