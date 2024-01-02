<?php
class User
{
    private $db;

    public function __construct()
    {
        // Connect to the database
        $this->db = new mysqli('localhost', 'root', '', 'user_manage');
    }

    // Register a new user
    public function register($name, $password, $cPassword, $email, $dob, $profile = null)
    {
        if (empty($name) || empty($password) || empty($cPassword) || empty($email) || empty($dob)) {
            return $this->messages(false,'some fields are missing'); // Required fields are missing
        }

        if ($password !== $cPassword) {
            return $this->messages(false,'password is not matching'); // Passwords do not match
        }

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $this->messages(false,'Email already registered'); // Email already registered
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $file_name = null;

        if (!empty($profile['name'])) {
            $file_name = time()."--".basename($profile["name"]);
            $target_dir = "assets/uploads/";
            $target_file = $target_dir . $file_name;

            if (!move_uploaded_file($profile["tmp_name"], $target_file)) {
                return $this->messages(false,'Failed to  upload file'); // Failed to move uploaded file
            }
        }

        $sql = "INSERT INTO users (name, password, email, dob, profile_image_path, created) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);

        if ($file_name !== null) {
            $stmt->bind_param("sssss", $name, $hashed_password, $email, $dob, $file_name);
        } else {
            $stmt->bind_param("sssss", $name, $hashed_password, $email, $dob, $file_name);
        }

        if($stmt->execute()){   // Insert user data
            return $this->messages(true,'User registered successfully');
        }else{
            return $this->messages(false,'Some error occured, Please try again');
        }
    }


    // User login
    public function login($email, $password)
    {
        // Retrieve user data by email
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            // User exists
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];
            // Verify the provided password with the stored hashed password
            if (password_verify($password, $hashed_password)) {
                // Password matches - start a session and set session variables
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['dob'] = $row['dob'];
                $_SESSION['profile_image_path'] = $row['profile_image_path'];
                return $this->messages(true,'Login Successful');
            } else {
                // Password does not match
                return $this->messages(false,'Invalid password');
            }
        } else {
            // User does not exist
            return $this->messages(false,'User Does Not Exist');
        }
    }

    // User logout
    public function logout()
    {
        // Unset session variables and destroy the session
        session_start();
        session_unset();
        session_destroy();
        return $this->messages(true,'Logout Successful');
    }

    public function messages($isSuccess,$msg){
          return ['success'=>$isSuccess,'msg'=>$msg];
    }

}
?>