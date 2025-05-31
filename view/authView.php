<?php 
session_start();

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$msg = '';

include_once '../model/authModel.php';
include_once '../controller/authController.php';

if ($action === 'login') {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $user = getUserByEmail($email);

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: ../view/exercise_library.php");
        exit();
    } else {
        $msg = "Invalid credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Fitness Tracker - User Authentication</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../asset/auth.css">
  <style>
    #signupFormSection, #forgotFormSection, #resetFormSection {
      display: none;
    }
    #loginFormSection {
      display: block;
    }
    .btn-contact {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #007BFF;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      transition: background-color 0.3s ease;
    }
    .btn-contact:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 id="formTitle">Login</h2>

    <?php if (!empty($msg)): ?>
      <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <!-- Login Form -->
    <section id="loginFormSection">
      <form method="POST">
        <input type="hidden" name="action" value="login">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <div>
          <span class="link" onclick="showForm('signup')">Signup</span> |
          <span class="link" onclick="showForm('forgot')">Forgot Password?</span>
        </div>
      </form>
      <!-- Contact Us Button -->
      <div style="margin-top: 20px; text-align: center;">
        <a href="../view/contactView.php" class="btn-contact">Contact Us</a>
      </div>
    </section>

    <!-- Signup Form -->
    <section id="signupFormSection">
      <form method="POST">
        <input type="hidden" name="action" value="signup">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Signup</button>
        <div>
          <span class="link" onclick="showForm('login')">Already have an account?</span>
        </div>
      </form>
    </section>

    <!-- Forgot Password Form -->
    <section id="forgotFormSection">
      <form method="POST">
        <input type="hidden" name="action" value="forgot">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send Reset Link</button>
        <div>
          <span class="link" onclick="showForm('login')">Back to login</span>
        </div>
      </form>
    </section>

    <!-- Reset Password Form -->
    <?php if ($action === 'reset' && isset($_GET['token'])): ?>
      <section id="resetFormSection" style="display: block;">
        <form method="POST">
          <input type="hidden" name="action" value="reset_submit">
          <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
          <input type="password" name="password" placeholder="New Password" required>
          <button type="submit">Reset Password</button>
        </form>
      </section>
    <?php endif; ?>
  </div>

  <script src="../asset/auth.js"></script>
</body>
</html>
