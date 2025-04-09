  <nav class="navbar navbar-light fixed-top bg-white" style="padding:0;min-height: 3.5rem">

     <div class="container-fluid mt-2 mb-2">
        <div class="row d-flex justify-content-end align-items-center">
           <?php
            include_once 'db_connect.php';
            $user = $conn->query("SELECT * FROM users where id = {$_SESSION['login_id']}")->fetch_array();
            ?>
           <h6 class="mt-4 mr-2">
              <?php echo ucwords($user['first_name'] . " " . $user['last_name']) ?>
           </h6>
           <div class="float-right">
              <div class=" dropdown mr-4">
                 <p></p>
                 <a href="#" class="text-dark " id="account_settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="<?php echo isset($user['avatar'])?$user['avatar']: 'assets/img/img.jpg'?>" width="36" height="36" class="rounded-circle">
                 </a>

                 <div class="dropdown-menu p-0" aria-labelledby="account_settings" style="left: -2.5em;">
                    <div class="dropdown-item noti-title profile-dropdown">
                       <h5 class="text3">Welcome</h5>
                    </div>
                    <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account"><i class="fa fa-cog text-muted"></i> Manage Account</a>
                    <a class="dropdown-item" href="ajax.php?action=logout"><i class="fa fa-power-off text-muted"></i> Logout</a>
                 </div>
              </div>
           </div>
        </div>
  </nav>
  <script>
     $('#manage_my_account').click(function() {
        uni_modal("Manage Account", "manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own")
     })
  </script>