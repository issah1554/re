<style>
    .collapse a {
        text-indent: 10px;
    }
</style>
<link rel="stylesheet" type="text/css" href="assets/css/matarial.css">
<nav id="sidebar" class='mx-lt-5 bg-white'>

    <div class="sidebar-list">
        <div id="sidebar-menu" class="sidebar-inner">
            <?php if ($_SESSION['login_type'] == 1): ?>
                <ul class="p-0">
                    <a href="index.php?page=<?php echo base64_encode('home'); ?>" class="nav-item nav-home">
                        <span class='icon-field'><i data-feather="airplay"></i></span> Dashboard
                    </a>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="nav-item nav-categories waves-effect">
                            <i data-feather="align-justify"></i>
                            <span> House Type</span> <span class="float-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled">
                            <li><a href="index.php?page=<?php echo base64_encode('categories'); ?>">Add</a></li>
                            <li><a href="index.php?page=<?php echo base64_encode('manage_categories'); ?>">Manage</a></li>
                        </ul>
                    </li>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="nav-item nav-categories waves-effect">
                            <i data-feather="home"></i>
                            <span> House</span> <span class="float-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled">
                            <li><a href="index.php?page=<?php echo base64_encode('houses'); ?>">Add</a></li>
                            <li><a href="index.php?page=<?php echo base64_encode('manage_houses'); ?>">Manage</a></li>
                        </ul>
                    </li>

                    <a href="index.php?page=<?php echo base64_encode('tenants'); ?>" class="nav-item nav-tenants">
                        <span class='icon-field'><i data-feather="user"></i></span> Tenants
                    </a>

                    <a href="index.php?page=<?php echo base64_encode('invoices'); ?>" class="nav-item nav-invoices">
                        <span class='icon-field'><i data-feather="credit-card"></i></span> Payments
                    </a>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="nav-item nav-categories waves-effect">
                            <i data-feather="align-justify"></i>
                            <span> Reports</span> <span class="float-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled">
                            <li><a href="index.php?page=<?php echo base64_encode('payment_report'); ?>">Monthly Payments Report</a></li>
                            <li><a href="index.php?page=<?php echo base64_encode('balance_report'); ?>">Rental Balances Report</a></li>
                        </ul>
                    </li>

                    <a href="index.php?page=<?php echo base64_encode('users'); ?>" class="nav-item nav-users">
                        <span class='icon-field'><i data-feather="users"></i></span> Users
                    </a>
                </ul>
            <?php elseif ($_SESSION['login_type'] == 2): ?>
                <ul class="p-0">
                    <a href="index.php?page=<?php echo base64_encode('views/owner/home'); ?>" class="nav-item nav-home">
                        <span class='icon-field'><i data-feather="airplay"></i></span> Dashboard
                    </a>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="nav-item nav-categories waves-effect">
                            <i data-feather="home"></i>
                            <span> House</span> <span class="float-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled">
                            <li><a href="index.php?page=<?php echo base64_encode('views/owner/houses'); ?>">Add</a></li>
                            <li><a href="index.php?page=<?php echo base64_encode('manage_houses'); ?>">Manage</a></li>
                        </ul>
                    </li>

                    <a href="index.php?page=<?php echo base64_encode('tenants'); ?>" class="nav-item nav-tenants">
                        <span class='icon-field'><i data-feather="users"></i></span> Your Tenants
                    </a>
                    <a href="index.php?page=<?php echo base64_encode('tenants'); ?>" class="nav-item nav-managers">
                        <span class='icon-field'><i data-feather="users"></i></span> Your Managers
                    </a>
                    <a href="index.php?page=<?php echo base64_encode('invoices'); ?>" class="nav-item nav-invoices">
                        <span class='icon-field'><i data-feather="credit-card"></i></span> Payments
                    </a>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="nav-item nav-categories waves-effect">
                            <i data-feather="align-justify"></i>
                            <span> Reports</span> <span class="float-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled">
                            <li><a href="index.php?page=<?php echo base64_encode('payment_report'); ?>">Monthly Payments Report</a></li>
                            <li><a href="index.php?page=<?php echo base64_encode('balance_report'); ?>">Rental Balances Report</a></li>
                        </ul>
                    </li>
                </ul>
            <?php elseif ($_SESSION['login_type'] == 3): ?>
                <ul class="p-0">
                    <a href="index.php?page=<?php echo base64_encode('views/manager/home'); ?>" class="nav-item nav-home">
                        <span class='icon-field'><i data-feather="airplay"></i></span> Dashboard
                    </a>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="nav-item nav-categories waves-effect">
                            <i data-feather="home"></i>
                            <span> House</span> <span class="float-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled">
                            <li><a href="index.php?page=<?php echo base64_encode('views/manager/houses'); ?>">Add</a></li>
                            <li><a href="index.php?page=<?php echo base64_encode('views/manager/manage_houses'); ?>">Manage</a></li>
                        </ul>
                    </li>

                    <a href="index.php?page=<?php echo base64_encode('views/manager/tenants'); ?>" class="nav-item nav-tenants">
                        <span class='icon-field'><i data-feather="users"></i></span> Your Tenants
                    </a>
                    <a href="index.php?page=<?php echo base64_encode('views/manager/tenants'); ?>" class="nav-item nav-managers">
                        <span class='icon-field'><i data-feather="users"></i></span> Your Managers
                    </a>
                    <a href="index.php?page=<?php echo base64_encode('views/manager/invoices'); ?>" class="nav-item nav-invoices">
                        <span class='icon-field'><i data-feather="credit-card"></i></span> Payments
                    </a>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="nav-item nav-categories waves-effect">
                            <i data-feather="align-justify"></i>
                            <span> Reports</span> <span class="float-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled">
                            <li><a href="index.php?page=<?php echo base64_encode('views/manager/payment_report'); ?>">Monthly Payments Report</a></li>
                            <li><a href="index.php?page=<?php echo base64_encode('views/manager/balance_report'); ?>">Rental Balances Report</a></li>
                        </ul>
                    </li>
                </ul>
                
            <?php elseif ($_SESSION['login_type'] == 4): ?>
                <ul class="p-0">
                    <a href="index.php?page=<?php echo base64_encode('views/owner/home'); ?>" class="nav-item nav-home">
                        <span class='icon-field'><i data-feather="airplay"></i></span> Dashboard
                    </a>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="nav-item nav-categories waves-effect">
                            <i data-feather="home"></i>
                            <span> House</span> <span class="float-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled">
                            <li><a href="index.php?page=<?php echo base64_encode('views/owner/houses'); ?>">Add</a></li>
                            <li><a href="index.php?page=<?php echo base64_encode('manage_houses'); ?>">Manage</a></li>
                        </ul>
                    </li>

                    <a href="index.php?page=<?php echo base64_encode('tenants'); ?>" class="nav-item nav-tenants">
                        <span class='icon-field'><i data-feather="users"></i></span> Your Tenants
                    </a>
                    <a href="index.php?page=<?php echo base64_encode('tenants'); ?>" class="nav-item nav-managers">
                        <span class='icon-field'><i data-feather="users"></i></span> Your Managers
                    </a>
                    <a href="index.php?page=<?php echo base64_encode('invoices'); ?>" class="nav-item nav-invoices">
                        <span class='icon-field'><i data-feather="credit-card"></i></span> Payments
                    </a>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="nav-item nav-categories waves-effect">
                            <i data-feather="align-justify"></i>
                            <span> Reports</span> <span class="float-right">
                                <i class="mdi mdi-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="list-unstyled">
                            <li><a href="index.php?page=<?php echo base64_encode('payment_report'); ?>">Monthly Payments Report</a></li>
                            <li><a href="index.php?page=<?php echo base64_encode('balance_report'); ?>">Rental Balances Report</a></li>
                        </ul>
                    </li>
                </ul>

            <?php endif; ?>


        </div>
    </div>

</nav>

<script>
    $('.nav_collapse').click(function() {
        console.log($(this).attr('href'))
        $($(this).attr('href')).collapse()
    })
    $('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>
<script src="https://unpkg.com/feather-icons@4.29.1/dist/feather.min.js"></script>
<script>
    feather.replace();
</script>

<script>
    $(document).ready(function() {
        // Hide all submenus by default
        $('.has_sub ul').hide();

        // Bind click event to menu items with submenus
        $('.has_sub > a').click(function(e) {
            e.preventDefault(); // Prevent default link behavior
            var $submenu = $(this).next('ul'); // Find the submenu

            // Close all other submenus
            $('.has_sub ul').not($submenu).slideUp();

            // Toggle the clicked submenu's visibility
            $submenu.slideToggle();

            // Toggle active class on the clicked menu item
            $(this).toggleClass('nav-active');
        });
    });
</script>