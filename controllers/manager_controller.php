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
    
    // Create new tenant
    public function create_tenant()
    {
        // Verify session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['login_id'])) {
            return json_encode(['status' => 'error', 'msg' => 'Session expired. Please login again.']);
        }

        // Verify manager session
        if (!isset($_SESSION['login_id']) || $_SESSION['login_type'] != 3) {
            return json_encode(['status' => 'error', 'msg' => 'Unauthorized access']);
        }

        // Validate required fields
        $required = ['tenant_fname', 'tenant_lname', 'tenant_phone', 'tenant_email'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                return json_encode(['status' => 'error', 'msg' => "Please fill all required fields"]);
            }
        }

        // Sanitize inputs
        $first_name = $this->sanitize($_POST['tenant_fname']);
        $last_name = $this->sanitize($_POST['tenant_lname']);
        $phone = $this->sanitize($_POST['tenant_phone']);
        $email = $this->sanitize($_POST['tenant_email']);
        $gender = $this->sanitize($_POST['gender'] ?? 'M');
        $manager_id = $_SESSION['login_id'];
        $apartment_id = $_POST['apartment_id'] ?? null; // Optional field
        $password = password_hash('rental', PASSWORD_DEFAULT); // Default password

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return json_encode(['status' => 'error', 'msg' => 'Invalid email format']);
        }

        // Check if email exists
        if ($this->email_exists($email)) {
            return json_encode(['status' => 'error', 'msg' => 'Email already in use']);
        }

        // Handle file upload
        $avatar_path = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
            $upload = $this->upload_avatar($_FILES['avatar']);
            if ($upload['status'] == 'error') {
                return json_encode(['status' => 'error', 'msg' => $upload['msg']]);
            }
            $avatar_path = $upload['path'];
        }

        // Create tenant account
        try {
            $this->db->begin_transaction();

            // Insert user
            $stmt = $this->db->prepare("INSERT INTO users 
                (first_name, last_name, username, password, phone, gender, type, avatar) 
                VALUES (?, ?, ?, ?, ?, ?, 4, ?)");
            $stmt->bind_param("sssssss", $first_name, $last_name, $email, $password, $phone, $gender, $avatar_path);

            if (!$stmt->execute()) {
                throw new Exception('Failed to create tenant account');
            }

            $tenant_id = $stmt->insert_id;
            $stmt->close();

            $this->db->commit();

            // assign tenant to apartment
            $stmt = $this->db->prepare("UPDATE apartments SET tenant_id = ? WHERE id = ?");
            $stmt->bind_param("ii", $tenant_id, $apartment_id);
            if (!$stmt->execute()) {
                throw new Exception('Failed to assign tenant to apartment');
            }
            $stmt->close();

            return json_encode([
                'status' => 'success',
                'msg' => 'Tenant created successfully',
                'tenant_id' => $tenant_id
            ]);
        } catch (Exception $e) {
            $this->db->rollback();
            return json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }

    // Helper method to sanitize inputs
    private function sanitize($input)
    {
        return $this->db->real_escape_string(htmlspecialchars(strip_tags(trim($input))));
    }

    // Check if email exists
    private function email_exists($email)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    // Handle avatar upload
    private function upload_avatar($file)
    {
        $uploadDir = 'uploads/avatars/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            return ['status' => 'error', 'msg' => 'Only JPG and PNG files are allowed'];
        }

        if ($file['size'] > $maxSize) {
            return ['status' => 'error', 'msg' => 'File size exceeds 5MB limit'];
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'tenant_' . time() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return ['status' => 'success', 'path' => $destination];
        }

        return ['status' => 'error', 'msg' => 'Failed to upload file'];
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
}
