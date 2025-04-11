<?php
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

    function assign_tenant()
    {
        // Verify session first
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['login_id'])) {
            return json_encode([
                'status' => 'error',
                'msg' => 'Session expired. Please login again.'
            ]);
        }

        // Validate input
        if (empty($_POST['tenant']) || empty($_POST['apartment_id'])) {
            return json_encode([
                'status' => 'error',
                'msg' => 'Missing required fields'
            ]);
        }

        try {
            $tenant_id = (int)$_POST['tenant'];
            $apartment_id = (int)$_POST['apartment_id'];

            $stmt = $this->db->prepare("UPDATE apartments SET tenant_id = ? WHERE id = ?");
            $stmt->bind_param("ii", $tenant_id, $apartment_id);

            if ($stmt->execute()) {
                return json_encode([
                    'status' => 'success',
                    'msg' => 'Tenant assignment updated successfully'
                ]);
            } else {
                throw new Exception('Database update failed');
            }
        } catch (Exception $e) {
            return json_encode([
                'status' => 'error',
                'msg' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    // Add this method to your Action class
    function set_apartment_free()
    {
        // Verify session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['login_id']) || $_SESSION['login_type'] != 3) {
            return json_encode([
                'status' => 'error',
                'msg' => 'Unauthorized access'
            ]);
        }

        // Validate input
        if (empty($_POST['apartment_id'])) {
            return json_encode([
                'status' => 'error',
                'msg' => 'Apartment ID is required'
            ]);
        }

        try {
            $apartment_id = (int)$_POST['apartment_id'];

            // Verify the apartment belongs to this manager
            $check = $this->db->prepare("SELECT id FROM apartments WHERE id = ? AND manager_id = ?");
            $check->bind_param("ii", $apartment_id, $_SESSION['login_id']);
            $check->execute();

            if (!$check->get_result()->num_rows) {
                throw new Exception('Apartment not found or not under your management');
            }

            // Set tenant_id to NULL
            $stmt = $this->db->prepare("UPDATE apartments SET tenant_id = NULL WHERE id = ?");
            $stmt->bind_param("i", $apartment_id);

            if ($stmt->execute()) {
                return json_encode([
                    'status' => 'success',
                    'msg' => 'Apartment has been set free successfully'
                ]);
            } else {
                throw new Exception('Database update failed');
            }
        } catch (Exception $e) {
            return json_encode([
                'status' => 'error',
                'msg' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function create_tenant()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['login_id']) || $_SESSION['login_type'] != 3) {
            return json_encode(['status' => 'error', 'msg' => 'Unauthorized access']);
        }

        $required = ['tenant_fname', 'tenant_lname', 'tenant_phone', 'tenant_email'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                return json_encode(['status' => 'error', 'msg' => "Please fill all required fields"]);
            }
        }

        // Sanitize inputs
        $db = $this->db;
        $first_name = $db->real_escape_string(strip_tags(trim($_POST['tenant_fname'])));
        $last_name = $db->real_escape_string(strip_tags(trim($_POST['tenant_lname'])));
        $phone = $db->real_escape_string(strip_tags(trim($_POST['tenant_phone'])));
        $email = $db->real_escape_string(strip_tags(trim($_POST['tenant_email'])));
        $gender = isset($_POST['gender']) ? $db->real_escape_string(strip_tags(trim($_POST['gender']))) : 'M';
        $manager_id = $_SESSION['login_id'];
        $password = password_hash('rental', PASSWORD_DEFAULT);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return json_encode(['status' => 'error', 'msg' => 'Invalid email format']);
        }

        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return json_encode(['status' => 'error', 'msg' => 'Email already in use']);
        }
        $stmt->close();

        // Avatar upload
        $avatar_path = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['avatar'];
            $allowedTypes = ['image/jpeg', 'image/png'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            if (!in_array($file['type'], $allowedTypes)) {
                return json_encode(['status' => 'error', 'msg' => 'Only JPG and PNG files are allowed']);
            }

            if ($file['size'] > $maxSize) {
                return json_encode(['status' => 'error', 'msg' => 'File size exceeds 5MB']);
            }

            $uploadDir = 'uploads/avatars/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'tenant_' . time() . '.' . $extension;
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $avatar_path = $destination;
            } else {
                return json_encode(['status' => 'error', 'msg' => 'Failed to upload avatar']);
            }
        }

        try {
            $db->begin_transaction();

            // Insert user
            $stmt = $db->prepare("INSERT INTO users 
            (first_name, last_name, username, password, phone, gender, type, avatar,created_by, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, 4, ?, ?, NOW())");
            $stmt->bind_param("ssssssss", $first_name, $last_name, $email, $password, $phone, $gender, $filename, $manager_id);

            if (!$stmt->execute()) {
                throw new Exception('Failed to create tenant account');
            }

            $tenant_id = $stmt->insert_id;
            $stmt->close();
            // Commit the transaction
            $db->commit();

            return json_encode([
                'status' => 'success',
                'msg' => 'Tenant created successfully',
                'tenant_id' => $tenant_id
            ]);
        } catch (Exception $e) {
            $db->rollback();
            return json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }

    function save_payment()
    {
        // Get POST data
        $tenant_id = $_POST['tenant_id'] ?? '';
        $amount = $_POST['amount'] ?? '';
        $payment_date = $_POST['payment_date'] ?? '';
        $payment_method = $_POST['payment_method'] ?? '';
        $from_date = $_POST['from_date'] ?? '';
        $to_date = $_POST['to_date'] ?? '';

        // Validate required fields
        if (empty($tenant_id) || empty($amount) || empty($payment_date) || empty($from_date) || empty($to_date)) {
            return json_encode([
                'status' => 'error',
                'message' => 'All fields are required'
            ]);
        }

        // Validate amount is numeric
        if (!is_numeric($amount) || $amount <= 0) {
            return json_encode([
                'status' => 'error',
                'message' => 'Amount must be a positive number'
            ]);
        }

        // Validate dates
        $payment_date_obj = DateTime::createFromFormat('Y-m-d', $payment_date);
        $from_date_obj = DateTime::createFromFormat('Y-m-d', $from_date);
        $to_date_obj = DateTime::createFromFormat('Y-m-d', $to_date);

        if (!$payment_date_obj || !$from_date_obj || !$to_date_obj) {
            return json_encode([
                'status' => 'error',
                'message' => 'Invalid date format'
            ]);
        }

        // Check date ranges
        if ($to_date_obj <= $from_date_obj) {
            return json_encode([
                'status' => 'error',
                'message' => 'End date must be after start date'
            ]);
        }

        // Prepare data for database
        $data = [
            'tenant_id' => $this->db->real_escape_string($tenant_id),
            'amount' => floatval($amount),
            'payment_date' => $payment_date_obj->format('Y-m-d'),
            'payment_method' => $payment_method,
            'from_date' => $from_date_obj->format('Y-m-d'),
            'to_date' => $to_date_obj->format('Y-m-d'),
            'created_by' => $_SESSION['login_id'],
            'date_created' => date('Y-m-d H:i:s')
        ];

        // Build SQL query
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $sql = "INSERT INTO payments ($columns) VALUES ($values)";

        // Execute query
        try {
            $result = $this->db->query($sql);

            if ($result) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Payment recorded successfully'
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Database error: ' . $this->db->error
                ]);
            }
        } catch (Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }

    function save_contract()
    {
        // Get POST data
        $tenant_id = $_POST['tenant_id'] ?? '';
        $apartment_id = $_POST['apartment_id'] ?? '';
        $from_date = $_POST['from_date'] ?? '';
        $to_date = $_POST['to_date'] ?? '';
        $has_family = isset($_POST['has_family']) && $_POST['has_family'] == 'yes' ? 1 : 0;
        $lease_type = $_POST['lease_type'] ?? 'resident';
        $witness_fname = $_POST['witness_fname'] ?? '';
        $witness_lname = $_POST['witness_lname'] ?? '';
        $witness_phone = $_POST['witness_phone'] ?? '';
        $manager_id = $_SESSION['login_id'];

        // Validate required fields
        if (empty($tenant_id) || empty($apartment_id) || empty($from_date) || empty($to_date)) {
            return json_encode([
                'status' => 'error',
                'message' => 'All required fields must be filled'
            ]);
        }

        // Validate dates
        $start_date = date('Y-m-d', strtotime($from_date));
        $end_date = date('Y-m-d', strtotime($to_date));

        if (!$start_date || !$end_date) {
            return json_encode([
                'status' => 'error',
                'message' => 'Invalid date format'
            ]);
        }

        if ($end_date <= $start_date) {
            return json_encode([
                'status' => 'error',
                'message' => 'End date must be after start date'
            ]);
        }

        // Handle file upload
        $contract_file = '';
        if (isset($_FILES['contract_file']) && $_FILES['contract_file']['error'] == UPLOAD_ERR_OK) {
            $file = $_FILES['contract_file'];
            $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);

            // Validate file type (only allow PDF)
            $allowed_types = ['pdf'];
            if (!in_array(strtolower($file_ext), $allowed_types)) {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Only PDF files are allowed for contract documents'
                ]);
            }

            // Generate unique filename
            $filename = 'contract_' . time() . '_' . $tenant_id . '.' . $file_ext;
            $upload_path = 'uploads/contracts/';

            // Create directory if it doesn't exist
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $upload_path . $filename)) {
                $contract_file = $upload_path . $filename;
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Failed to upload contract file'
                ]);
            }
        }

        // Check if apartment is available
        $check = $this->db->query("SELECT * FROM apartments WHERE id = '$apartment_id'");
        if ($check->num_rows == 0) {
            return json_encode([
                'status' => 'error',
                'message' => 'The selected property is not available for rent'
            ]);
        }

        // Prepare data for database
        $data = [
            'tenant_id' => $this->db->real_escape_string($tenant_id),
            'apartment_id' => $this->db->real_escape_string($apartment_id),
            'from_date' => $start_date,
            'to_date' => $end_date,
            'lease_type' => $this->db->real_escape_string($lease_type),
            'has_family' => $has_family,
            'witness_first_name' => $this->db->real_escape_string($witness_fname),
            'witness_last_name' => $this->db->real_escape_string($witness_lname),
            'witness_phone' => $this->db->real_escape_string($witness_phone),
            'contract_file' => $this->db->real_escape_string($filename),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Build SQL query
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $sql = "INSERT INTO contracts ($columns) VALUES ($values)";

        // Execute query within transaction
        $this->db->begin_transaction();
        try {
            // Save contract
            $result = $this->db->query($sql);
            if (!$result) {
                throw new Exception('Failed to save contract: ' . $this->db->error);
            }


            $this->db->commit();

            return json_encode([
                'status' => 'success',
                'message' => 'Contract saved successfully'
            ]);
        } catch (Exception $e) {
            $this->db->rollback();

            // Delete uploaded file if transaction failed
            if (!empty($contract_file) && file_exists($contract_file)) {
                unlink($contract_file);
            }

            return json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }}
