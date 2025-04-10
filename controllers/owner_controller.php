<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 1);

class Action
{
    private $db;

    public function __construct()
    {
        ob_start();
        include 'db_connect.php';
        $this->db = $conn; // this is your db connection
    }

    function __destruct()
    {
        $this->db->close();
        ob_end_flush();
    }
    public function add_apartment() {
        if (!isset($_SESSION['login_id'])) {
            return json_encode(['status' => 'error', 'message' => 'User not authenticated']);
        }
    
        $owner_id = $_SESSION['login_id'];
    
        $id = isset($_POST['id']) && $_POST['id'] !== '' ? intval($_POST['id']) : 0;
        $number = $this->db->real_escape_string($_POST['number']);
        $description = $this->db->real_escape_string($_POST['description']);
        $price = floatval($_POST['price']);
        $category_id = intval($_POST['category_id']);
    
        if ($id > 0) {
            // Update existing apartment
            $stmt = $this->db->prepare("UPDATE apartments SET number = ?, description = ?, price = ?, category_id = ? WHERE id = ? AND owner_id = ?");
            $stmt->bind_param("ssdiii", $number, $description, $price, $category_id, $id, $owner_id);
            if ($stmt->execute()) {
                return json_encode(['status' => 'success', 'message' => 'Apartment successfully updated.']);
            } else {
                return json_encode(['status' => 'error', 'message' => 'Update failed: ' . $this->db->error]);
            }
        } else {
            // Check for duplicate number
            $check = $this->db->query("SELECT * FROM apartments WHERE number = '$number' AND owner_id = $owner_id");
            if ($check && $check->num_rows > 0) {
                return json_encode(['status' => 'error', 'message' => 'Apartment number already exists for this owner.']);
            }
    
            // Insert new apartment
            $stmt = $this->db->prepare("INSERT INTO apartments (number, description, price, category_id, owner_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdii", $number, $description, $price, $category_id, $owner_id);
            if ($stmt->execute()) {
                return json_encode(['status' => 'success', 'message' => 'Apartment successfully added.']);
            } else {
                return json_encode(['status' => 'error', 'message' => 'Insert failed: ' . $this->db->error]);
            }
        }
    }
    
    

    public function create_manager()
    {
        // Verify session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        if (!isset($_SESSION['login_id'])) {
            return json_encode(['status' => 'error', 'msg' => 'Session expired. Please login again.']);
        }
    
        // Verify owner session
        if (!isset($_SESSION['login_id']) || $_SESSION['login_type'] != 2) {
            return json_encode(['status' => 'error', 'msg' => 'Unauthorized access']);
        }
    
        // Validate required fields
        $required = ['manager_fname', 'manager_lname', 'manager_phone', 'manager_email'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                return json_encode(['status' => 'error', 'msg' => "Please fill all required fields"]);
            }
        }
    
        // Sanitize inputs
        $first_name = $this->sanitize($_POST['manager_fname']);
        $last_name = $this->sanitize($_POST['manager_lname']);
        $phone = $this->sanitize($_POST['manager_phone']);
        $email = $this->sanitize($_POST['manager_email']);
        $gender = $this->sanitize($_POST['gender'] ?? 'male');
        $manager_id = $_SESSION['login_id'];
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
            $name = $first_name . ' ' . $last_name;
    
            // Insert user, linking the manager to the owner who created them
            $stmt = $this->db->prepare("INSERT INTO users
                (name, first_name, last_name, username, password, phone, gender, type, avatar, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 3, ?, ?)"); // '3' means Manager, and 'created_by' links the user to the owner
    
            $stmt->bind_param("ssssssssi", $name, $first_name, $last_name, $email, $password, $phone, $gender, $avatar_path, $_SESSION['login_id']);
    
            if (!$stmt->execute()) {
                throw new Exception('Failed to create manager account');
            }
    
            $manager_id = $stmt->insert_id;
            $stmt->close();
    
            $this->db->commit();
    
            return json_encode([
                'status' => 'success',
                'msg' => 'Manager created successfully',
                'manager_id' => $manager_id
            ]);
        } catch (Exception $e) {
            $this->db->rollback();
            return json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    

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
 
    


     public function get_manager() {
        // Ensure the session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Make sure the user is logged in
        if (!isset($_SESSION['login_id'])) {
            return json_encode(['status' => 'error', 'msg' => 'User not authenticated']);
        }
    
        // Get the logged-in user's ID (the owner ID)
        $owner_id = $_SESSION['login_id'];
    
        // Prepare the query to select managers created by the specific owner
        $stmt = $this->db->prepare("SELECT id, CONCAT(first_name, ' ', last_name) AS name, phone, username AS email 
                                    FROM users 
                                    WHERE type = 3 AND created_by = ?");
        $stmt->bind_param("i", $owner_id); // Bind the owner ID to the query
    
        $stmt->execute();
        $result = $stmt->get_result();
    
        $managers = [];
        while ($row = $result->fetch_assoc()) {
            $managers[] = $row;
        }
    
        // Return the list of managers as JSON
        return json_encode(['status' => 'success', 'data' => $managers]);
    }

    
    public function assign_manager() {
        if (!isset($_POST['apartment_id'], $_POST['manager_id'])) {
            return json_encode(['status' => 'error', 'message' => 'Missing parameters']);
        }
    
        $apartmentId = $_POST['apartment_id'];
        $managerId = $_POST['manager_id'];
    
        // Ensure the database connection is established
        global $conn;
        if (!$conn) {
            return json_encode(['status' => 'error', 'message' => 'Database connection failed']);
        }
    
        // Example: Update the apartment's manager_id
        try {
            $stmt = $conn->prepare("UPDATE apartments SET manager_id = ? WHERE id = ?");
            if ($stmt->execute([$managerId, $apartmentId])) {
                return json_encode(['status' => 'success', 'message' => 'Manager assigned successfully']);
            } else {
                return json_encode(['status' => 'error', 'message' => 'Failed to assign manager']);
            }
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
   // Get Manager Details
    public function get_manager_details($managerId) {
        // Include database connection if it's not already included
        include 'db_connect.php';
        
        // Fetch manager details based on manager ID
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $managerId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $manager = $result->fetch_assoc();
            
            // Fetch apartments assigned to this manager
            $stmt_apartments = $conn->prepare("SELECT * FROM apartments WHERE manager_id = ?");
            $stmt_apartments->bind_param("i", $managerId);
            $stmt_apartments->execute();
            $apartments_result = $stmt_apartments->get_result();

            $apartments = [];
            while ($apartment = $apartments_result->fetch_assoc()) {
                $apartments[] = $apartment;
            }

            // Return the manager and apartments data
            return json_encode([
                'status' => 'success',
                'data' => [
                    'manager' => $manager,
                    'apartments' => $apartments
                ]
            ]);
        } else {
            return json_encode(['status' => 'error', 'msg' => 'Manager not found']);
        }
    }

        // Remove Apartment from Manager
    public function remove_apartment($apartmentId, $managerId) {
        // Include database connection if it's not already included
        include 'db_connect.php';

        // Prepare query to remove the manager from the apartment
        $stmt = $conn->prepare("UPDATE apartments SET manager_id = NULL WHERE id = ? AND manager_id = ?");
        $stmt->bind_param("ii", $apartmentId, $managerId);

        // Execute query and check if the operation was successful
        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'message' => 'Apartment removed successfully']);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Failed to remove apartment']);
        }
    }


}


  ?>