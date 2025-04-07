<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

$action = $_GET['action'] ?? ''; // Null coalescing for PHP < 7.0 compatibility

if ($action == 'login') {
	include 'db_connect.php';

	// Check if username and password are provided
	if (empty($_POST['username']) || empty($_POST['password'])) {
		echo 0; // Invalid input
		exit;
	}

	$username = $_POST['username'];

	// Prepare and execute query
	$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?"); // Assuming $conn is your connection variable from db_connect.php
	$stmt->bind_param("s", $username);

	if (!$stmt->execute()) {
		echo "Database error";
		exit;
	}

	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		$user = $result->fetch_assoc();

		// Verify password
		if (password_verify($_POST['password'], $user['password'])) {
			// Set session variables
			$_SESSION['login_id'] = $user['id'];
			$_SESSION['login_type'] = $user['type'];
			$_SESSION['login_name'] = $user['first_name'] . ' ' . $user['last_name'];
			$_SESSION['login_username'] = $user['username'];
			echo 1; // Successful login
			exit;
		}
	}

	echo 2; // Invalid credentials
	exit;
}

if ($action == 'logout') {
	// Clear session data
	$_SESSION = array();

	// Destroy the session
	session_destroy();

	// Redirect to login page
	header("Location: login.php");
	exit;
}


if ($_SESSION['login_type'] == 1) {
	include 'admin_class.php';
	$crud = new Action();

	if ($action == "save_category") {
		$save = $crud->save_category();
		if ($save)
			echo $save;	}

	if ($action == "edit_category") {
		$save = $crud->edit_category();
		if ($save)
			echo $save;
	}

	if ($action == "delete_category") {
		$delete = $crud->delete_category();
		if ($delete)
			echo $delete;
	}
	if ($action == "save_house") {
		$save = $crud->save_house();
		if ($save)
			echo $save;
	}
	if ($action == "delete_house") {
		$save = $crud->delete_house();
		if ($save)
			echo $save;
	}

	if ($action == "save_tenant") {
		$save = $crud->save_tenant();
		if ($save)
			echo $save;
	}
	if ($action == "delete_tenant") {
		$save = $crud->delete_tenant();
		if ($save)
			echo $save;
	}
	if ($action == "get_tdetails") {
		$get = $crud->get_tdetails();
		if ($get)
			echo $get;
	}

	if ($action == "save_payment") {
		$save = $crud->save_payment();
		if ($save)
			echo $save;
	}
	if ($action == "delete_payment") {
		$save = $crud->delete_payment();
		if ($save)
			echo $save;
	}

	// ADMIN USER MANAGEMENT
	//--------------------------------------------------

	if ($action == "create_user") {
		$create = $crud->create_user();
		if ($create)
			echo $create;
	}
	if ($action == 'get_user_id') {
		$get = $crud->get_user_id();
		if ($get)
			echo $get;
	}
	if ($action == 'update_user') {
		$save = $crud->update_user();
		if ($save)
			echo $save;
	}

	if ($action == 'delete_user') {
		$save = $crud->delete_user();
		if ($save)
			echo $save;
	}


	ob_end_flush();
} 

if ($_SESSION['login_type'] == 2) {  // Ensure the user is an owner (login type 4)
    include 'controllers/owner_controller.php';  // Include your owner controller
    $crud = new Action();

    // Handle adding an apartment
    if ($action == 'add_apartment') {
        $response = $crud->add_apartment();  // Call the add_apartment function
        echo $response;  // Output the response (success or error)
    }
}





if ($_SESSION['login_type'] == 3) {
	include 'controllers/manager_controller.php';
	$crud = new Action();

	if ($action == 'assign_tenant') {
		$save = $crud->assign_tenant();
		if ($save)
			echo $save;
	}

	// Add this to your ajax.php where other actions are handled
	if ($action == 'set_apartment_free') {
		$save = $crud->set_apartment_free();
		if ($save)
			echo $save;
	}

	if ($action == 'create_tenant') {
		$save = $crud->create_tenant();
		if ($save)
			echo $save;
	}
} 

else if ($_SESSION['login_type'] == 4) {
	include 'controllers/tenant_controller.php';
	$crud = new Action();

	if ($action == 'update_account') {
		$save = $crud->update_account();
		if ($save)
			echo $save;
	}

	if ($action == 'get_tdetails') {
		$get = $crud->get_tdetails();
		if ($get)
			echo $get;
	}
}
