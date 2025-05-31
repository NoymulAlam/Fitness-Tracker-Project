<?php  
session_start();

// Dummy user data example (replace with real DB query)
$user = [
  'name' => 'Noymul Alam',
  'email' => 'hellonoymul@example.com',
  'avatar' => '../uploads/default_avatar.png', // default avatar path
];

// Placeholder for messages (success/error)
$msg = '';

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  // Upload avatar
  if (isset($_POST['upload_avatar']) && isset($_FILES['avatar'])) {
    $file = $_FILES['avatar'];

    // Simple file validation (you should enhance security!)
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($file['type'], $allowed_types) && $file['error'] === 0) {
      $upload_dir = '../uploads/';  // Added trailing slash here
      $filename = uniqid() . '_' . basename($file['name']);
      $target_file = $upload_dir . $filename;

      if (move_uploaded_file($file['tmp_name'], $target_file)) {
        // TODO: Add crop/resize logic here (e.g. JS or PHP image functions)

        // Update user avatar path in DB - for demo update $user
        $user['avatar'] = $target_file;
        $msg = "Avatar uploaded successfully!";
      } else {
        $msg = "Error uploading avatar.";
      }
    } else {
      $msg = "Invalid file type or error.";
    }
  }

  // Update profile info
  if (isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Validate inputs (simplified)
    if ($name !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
      // Update user info in DB here (demo update $user)
      $user['name'] = $name;
      $user['email'] = $email;
      $msg = "Profile updated successfully!";
    } else {
      $msg = "Invalid name or email.";
    }
  }

  // Update password
  if (isset($_POST['update_password'])) {
    $password = $_POST['password'];

    if (strlen($password) >= 6) {
      // Hash and save new password in DB here
      // $hash = password_hash($password, PASSWORD_DEFAULT);
      $msg = "Password updated successfully!";
    } else {
      $msg = "Password must be at least 6 characters.";
    }
  }
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Profile Management</title>
  <link rel="stylesheet" href="../asset/profile.css" />
</head>
<body>
<div class="container">
  <h2>Your Profile</h2>

  <?php if (!empty($msg)): ?>
    <div class="msg"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <div style="text-align: center;">
    <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" class="avatar" />
  </div>

  <!-- Upload Avatar -->
  <form method="post" enctype="multipart/form-data" class="section">
    <h3>Upload Avatar</h3>
    <input type="file" name="avatar" accept="image/*" required />
    <button type="submit" name="upload_avatar">Save Avatar</button>
  </form>

  <!-- Edit Profile -->
  <form method="post" class="section">
    <h3>Edit Profile</h3>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required placeholder="Full Name" />
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required placeholder="Email Address" />
    <button type="submit" name="update_profile">Save Changes</button>
  </form>

  <!-- Update Password -->
  <form method="post" class="section">
    <h3>Update Password</h3>
    <input type="password" name="password" placeholder="New Password" required minlength="6" />
    <button type="submit" name="update_password">Update Password</button>
  </form>
</div>
</body>
</html>
