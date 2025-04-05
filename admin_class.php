<?php
session_start();
ini_set('display_errors', 1);

class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}

	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login() {
		extract($_POST);
		
		// Check if username and password are provided
		if(empty($username) || empty($password)) {
			return 0; // Invalid input
		}
	
		// Prepare and execute query
		$stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if($result->num_rows > 0) {
			$user = $result->fetch_assoc();
			
			// Verify password (assuming passwords are hashed)
			if(password_verify($password, $user['password'])) {
				// Set session variables
				$_SESSION['login_id'] = $user['id'];
				$_SESSION['login_type'] = $user['type'];
				$_SESSION['login_name'] = $user['first_name'] . ' ' . $user['last_name'];
				$_SESSION['login_username'] = $user['username'];								
				return 1; // Successful login
			}
		}
		
		return 2; // Invalid credentials
	}
	
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}

	function logout2()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function signup()
	{
		extract($_POST);
		$data = " name = '" . $firstname . ' ' . $lastname . "' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '" . md5($password) . "' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		$save = $this->db->query("INSERT INTO users set " . $data);
		if ($save) {
			$uid = $this->db->insert_id;
			$data = '';
			foreach ($_POST as $k => $v) {
				if ($k == 'password')
					continue;
				if (empty($data) && !is_numeric($k))
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if ($_FILES['img']['tmp_name'] != '') {
				$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
				$data .= ", avatar = '$fname' ";
			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if ($data) {
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				// $login = $this->login2();
				if ($login)
					return 1;
			}
		}
	}
	
	function update_account()
	{
		extract($_POST);
		$data = " name = '" . $firstname . ' ' . $lastname . "' ";
		$data .= ", username = '$email' ";
		if (!empty($password))
			$data .= ", password = '" . md5($password) . "' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if ($save) {
			$data = '';
			foreach ($_POST as $k => $v) {
				if ($k == 'password')
					continue;
				if (empty($data) && !is_numeric($k))
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if ($_FILES['img']['tmp_name'] != '') {
				$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
				$data .= ", avatar = '$fname' ";
			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if ($data) {
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				// $login = $this->login2();
				if ($login)
					return 1;
			}
		}
	}




	// ADMIN USER MANAGEMENT
	//--------------------------------------------------
	function create_user()
	{
		extract($_POST);

		// Validate input
		if (empty($username) || empty($password) || empty($first_name) || empty($last_name)) {
			return json_encode(['status' => 'error', 'message' => 'All fields are required']);
		}

		// Check if username already exists
		$check = $this->db->query("SELECT * FROM users WHERE username = '$username'");
		if ($check->num_rows > 0) {
			return json_encode(['status' => 'error', 'message' => 'Username already exists']);
		}

		// Hash password
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);

		// Insert new user
		$sql = "INSERT INTO users (username, password, type, first_name, last_name) 
                VALUES ('$username', '$hashed_password', '$type', '$first_name', '$last_name')";

		if ($this->db->query($sql)) {
			return json_encode(['status' => 'success', 'message' => 'User created successfully']);
		} else {
			return json_encode(['status' => 'error', 'message' => 'Error creating user: ' . $this->db->error]);
		}
	}

	function get_user_id()
	{
		$id = $_GET['id'];
		$sql = "SELECT * FROM users WHERE id = '$id'";
		$result = $this->db->query($sql);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			// Don't return password for security
			unset($row['password']);
			return json_encode($row);
		} else {
			return json_encode(['status' => 'error', 'message' => 'User not found']);
		}
	}

	function update_user()
	{
		// Get all POST data
		$post_data = $_POST;
		
		// Validate required fields
		if(empty($post_data['username']) || empty($post_data['first_name']) || empty($post_data['last_name'])) {
			return json_encode(['status' => 'error', 'message' => 'Username, First Name and Last Name are required']);
		}
	
		// Get and sanitize user_id
		$id = isset($post_data['user_id']) ? $this->db->real_escape_string($post_data['user_id']) : null;
		if(empty($id)) {
			return json_encode(['status' => 'error', 'message' => 'User ID is required']);
		}
	
		// Sanitize other inputs
		$username = $this->db->real_escape_string($post_data['username']);
		$type = $this->db->real_escape_string($post_data['type']);
		$first_name = $this->db->real_escape_string($post_data['first_name']);
		$last_name = $this->db->real_escape_string($post_data['last_name']);
	
		// Check if username exists for another user
		$check = $this->db->query("SELECT * FROM users WHERE username = '$username' AND id != '$id'");
		if($check->num_rows > 0) {
			return json_encode(['status' => 'error', 'message' => 'Username already exists for another user']);
		}
	
		// Prepare update query
		$update_fields = [
			"username = '$username'",
			"type = '$type'",
			"first_name = '$first_name'",
			"last_name = '$last_name'"
		];
	
		// Update password only if provided
		if(!empty($post_data['password'])) {
			$hashed_password = password_hash($post_data['password'], PASSWORD_DEFAULT);
			$update_fields[] = "password = '$hashed_password'";
		}
	
		$sql = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE id = '$id'";
	
		if($this->db->query($sql)) {
			return json_encode(['status' => 'success', 'message' => 'User updated successfully']);
		} else {
			return json_encode(['status' => 'error', 'message' => 'Error updating user: ' . $this->db->error]);
		}
	}
	
	function delete_user()
	{
		$id = $_POST['id'];

		// Prevent deleting yourself
		if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
			return json_encode(['status' => 'error', 'message' => 'You cannot delete your own account']);
		}

		$sql = "DELETE FROM users WHERE id = '$id'";

		if ($this->db->query($sql)) {
			return json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
		} else {
			return json_encode(['status' => 'error', 'message' => 'Error deleting user: ' . $this->db->error]);
		}
	}

	function save_settings()
	{
		extract($_POST);
		$data = " name = '" . str_replace("'", "&#x2019;", $name) . "' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '" . htmlentities(str_replace("'", "&#x2019;", $about)) . "' ";
		if ($_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", cover_img = '$fname' ";
		}

		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set " . $data);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set " . $data);
		}
		if ($save) {
			$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
			foreach ($query as $key => $value) {
				if (!is_numeric($key))
					$_SESSION['system'][$key] = $value;
			}

			return 1;
		}
	}


	function save_category()
	{
		extract($_POST);


		$data = " name = '$name' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO categories set $data");
		} else {
			$save = $this->db->query("UPDATE categories set $data where id = $id");
		}
		if ($save)
			return 1;
	}


	function edit_category()
	{
		// Check if the necessary POST data is set
		if (isset($_POST['name'], $_POST['id'])) {
			// Get the category name and ID from the POST data
			$name = $_POST['name'];
			$id = $_POST['id'];

			// Prepare the update query
			$stmt = $this->db->prepare("UPDATE categories SET name = ? WHERE id = ?");
			// Bind parameters
			$stmt->bind_param("si", $name, $id);
			// Execute the query
			$stmt->execute();

			// Check if the update was successful
			if ($stmt->affected_rows > 0) {
				// Close the statement
				$stmt->close();
				return 1; // Return success status
			} else {
				// If the update failed, get the error message
				$error_message = $this->db->error;
				// Close the statement
				$stmt->close();
				return $error_message; // Return the error message
			}
		} else {
			return "Required POST data is missing"; // Return failure status if POST data is missing
		}
	}



	function delete_category()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM categories where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	
	function save_house()
	{
		extract($_POST);
		$data = " house_no = '$house_no' ";
		$data .= ", description = '$description' ";
		$data .= ", category_id = '$category_id' ";
		$data .= ", price = '$price' ";
		$chk = $this->db->query("SELECT * FROM houses where house_no = '$house_no' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO houses set $data");
		} else {
			$save = $this->db->query("UPDATE houses set $data where id = $id");
		}
		if ($save)
			return 1;
	}
	function delete_house()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM houses where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_tenant()
	{
		extract($_POST);
		$data = " firstname = '$firstname' ";
		$data .= ", lastname = '$lastname' ";
		$data .= ", middlename = '$middlename' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", house_id = '$house_id' ";
		$data .= ", date_in = '$date_in' ";
		if (empty($id)) {

			$save = $this->db->query("INSERT INTO tenants set $data");
		} else {
			$save = $this->db->query("UPDATE tenants set $data where id = $id");
		}
		if ($save)
			return 1;
	}
	function delete_tenant()
	{
		extract($_POST);
		$delete = $this->db->query("UPDATE tenants set status = 0 where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function get_tdetails()
	{
		extract($_POST);
		$data = array();
		$tenants = $this->db->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as name,h.house_no,h.price FROM tenants t inner join houses h on h.id = t.house_id where t.id = {$id} ");
		foreach ($tenants->fetch_array() as $k => $v) {
			if (!is_numeric($k)) {
				$$k = $v;
			}
		}
		$months = abs(strtotime(date('Y-m-d') . " 23:59:59") - strtotime($date_in . " 23:59:59"));
		$months = floor(($months) / (30 * 60 * 60 * 24));
		$data['months'] = $months;
		$payable = abs($price * $months);
		$data['payable'] = number_format($payable, 2);
		$paid = $this->db->query("SELECT SUM(amount) as paid FROM payments where id != '$pid' and tenant_id =" . $id);
		$last_payment = $this->db->query("SELECT * FROM payments where id != '$pid' and tenant_id =" . $id . " order by unix_timestamp(date_created) desc limit 1");
		$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
		$data['paid'] = number_format($paid, 2);
		$data['last_payment'] = $last_payment->num_rows > 0 ? date("M d, Y", strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
		$data['outstanding'] = number_format($payable - $paid, 2);
		$data['price'] = number_format($price, 2);
		$data['name'] = ucwords($name);
		$data['rent_started'] = date('M d, Y', strtotime($date_in));

		return json_encode($data);
	}

	function save_payment()
	{
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'ref_code')) && !is_numeric($k)) {
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO payments set $data");
			$id = $this->db->insert_id;
		} else {
			$save = $this->db->query("UPDATE payments set $data where id = $id");
		}

		if ($save) {
			return 1;
		}
	}

	function delete_payment()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM payments where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
}
