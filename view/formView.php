<?php
// formModel.php logic embedded here
function handleFormSubmission($name, $email, $age) {
    $conn = new mysqli("localhost", "root", "", "fitness_tracker");
    if ($conn->connect_error) {
        return "❌ Database connection failed: " . $conn->connect_error;
    }

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $age > 0) {
        $stmt = $conn->prepare("INSERT INTO user_form_validation (name, email, age) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $email, $age);
        if ($stmt->execute()) {
            $conn->close();
            return "✅ Form submitted successfully! Thanks!";
        } else {
            $conn->close();
            return "❌ Error: Email already exists or submission failed.";
        }
    } else {
        $conn->close();
        return "❌ Invalid input. Please correct the form.";
    }
}

// formController.php logic embedded here
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $age = intval($_POST["age"] ?? 0);

    $msg = handleFormSubmission($name, $email, $age);
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Form Validation - Fitness Tracker</title>
  <link rel="stylesheet" href="../asset/form_validation_style.css">
</head>
<body>

<div class="container">
  <h2>Fitness Tracker Signup Form</h2>

  <?php if (!empty($msg)): ?>
    <div class="<?= strpos($msg, '✅') !== false ? 'success-msg' : 'error-msg' ?>">
      <?= htmlspecialchars($msg) ?>
    </div>
  <?php endif; ?>

  <form method="POST" onsubmit="return validateForm()">
    <label for="name">Full Name:</label>
    <input type="text" id="name" name="name" required oninput="validateName()" />
    <div class="error" id="nameError">Name must be at least 3 characters.</div>

    <label for="email">Email Address:</label>
    <input type="email" id="email" name="email" required oninput="validateEmail()" />
    <div class="error" id="emailError">Please enter a valid email address.</div>

    <label for="age">Age:</label>
    <input type="number" id="age" name="age" required oninput="validateAge()" />
    <div class="error" id="ageError">Age must be a positive number.</div>

    <button type="submit">Submit</button>
  </form>
</div>

<script src="asset/form_validation_script.js"></script>
</body>
</html>
