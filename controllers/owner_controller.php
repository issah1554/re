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
        // Make sure the user is logged in
        if (!isset($_SESSION['login_id'])) {
            return json_encode(['status' => 'error', 'message' => 'User not authenticated']);
        }
    
        $owner_id = $_SESSION['login_id'];
    
        // Sanitize input
        $number = $this->db->real_escape_string($_POST['number']);
        $description = $this->db->real_escape_string($_POST['description']);
        $price = floatval($_POST['price']);
        $category_id = intval($_POST['category_id']);
    
        // Check if apartment number already exists for this owner
        $check = $this->db->query("SELECT * FROM apartments WHERE number = '$number' AND owner_id = $owner_id");
        if ($check && $check->num_rows > 0) {
            return json_encode(['status' => 'error', 'message' => 'Apartment number already exists for this owner.']);
        }
    
        // Insert into database
        $stmt = $this->db->prepare("INSERT INTO apartments (number, description, price, category_id, owner_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdii", $number, $description, $price, $category_id, $owner_id);
    
        if ($stmt->execute()) {
            return json_encode(['status' => 'success', 'message' => 'Apartment successfully added.']);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Database error: ' . $this->db->error]);
        }
    }
    
}
?>
