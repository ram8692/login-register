<?php
session_start();
require_once 'User.php';
$user = new User();

// Handle registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $cPassword = $_POST['c_password'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $profile = $_FILES['profile'];

    $isRegister  = $user->register($name, $password, $cPassword, $email, $dob, $profile);
    if ($isRegister['success']) {
        // Redirect to task page after successful registration
        header("Location: index.php?success=".$isRegister['success']."&msg=".$isRegister['msg']);
        exit();
    } else {
        // Redirect to registration page with error message
        header("Location: register.php?success=".$isRegister['success']."&msg=".$isRegister['msg']);
        exit();
    }
}
require_once 'header.php'; ?>
<main class="form-signin w-100 m-auto">
<?php if (isset($_GET['success']) && $_GET['success'] == 1) { ?>
  <div class="alert alert-success" role="alert">
  <?= isset($_GET['msg']) ? $_GET['msg'] : ''?>
</div>
<?php } else if(isset($_GET['success'])) { ?>
  <div class="alert alert-danger" role="alert">
  <?= isset($_GET['msg']) ? $_GET['msg'] : ''?>
  </div>
<?php } ?>
    <form method="POST" enctype="multipart/form-data">
        <h1 class="h3 mb-3 fw-normal">Please Register</h1>

        <div class="form-floating mb-3">
            <input type="text" name="name" class="form-control" id="floatingName" placeholder="Name" required>
            <label for="floatingName">Name</label>
        </div> 
        <div class="form-floating mb-3">
            <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Email"  required>
            <label for="floatingEmail">Email address</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password"  required>
            <label for="floatingPassword">Password</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" name="c_password" class="form-control" id="floatingConfirmPassword"
                placeholder="Confirm Password"  required>
            <label for="floatingConfirmPassword">Confirm Password</label>
        </div>
        <div class="form-floating mb-3">
            <input type="file" name="profile" class="form-control" id="floatingProfile" placeholder="Profile Pic">
            <label for="floatingProfile">Profile Pic</label>
        </div>
        <div class="form-floating mb-3">
            <input type="date" name="dob" class="form-control" id="floatingDOB" placeholder="Date of Birth"  required>
            <label for="floatingDOB">Date of Birth</label>
        </div>

        <input type="submit" name="register" class="btn btn-primary w-100 py-2" value="Register">
        
    </form>
    <a href="login.php" class="btn btn-info w-100 py-2">Login</a>
</main>
<?php require_once 'footer.php'; ?>