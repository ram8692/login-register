<?php
session_start();
// Check if user is logged in, if not redirect to login page
if (isset($_SESSION['user_id'])) {
  header("Location: index.php?msg=");
  exit();
}

require_once 'User.php';

$user = new User();

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $isLogin  = $user->login($email, $password);
  if ($isLogin['success']) {

    // Redirect to dashboard after successful login
    header("Location: index.php?success=".$isLogin['success']."&msg=".$isLogin['msg']);
    exit();
  } else {

    // Redirect to login page with error message
    header("Location: login.php?success=".$isLogin['success']."&msg=".$isLogin['msg']);
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

    <form method="POST">

        <h1 class="h3 mb-3 fw-normal">Please Login</h1>

        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput"  name="email"  placeholder="name@example.com" required>
            <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
            <input type="password"  name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
            <label for="floatingPassword">Password</label>
        </div>

        <input type="submit" name="login" class="btn btn-primary w-100 py-2" value="Login">
    </form>
    <a href="register.php" class="btn btn-info w-100 py-2">Register</a>
</main>
<?php require_once 'footer.php'; ?>