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

    function login()
    {
        extract($_POST);

        // Check if username and password are provided
        if (empty($username) || empty($password)) {
            return 0; // Invalid input
        }

        // Prepare and execute query
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password (assuming passwords are hashed)
            if (password_verify($password, $user['password'])) {
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
}
