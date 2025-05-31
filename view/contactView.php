<?php
include_once '../model/contactModel.php';
include_once '../controller/contactController.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Contact Us - Fitness Tracker</title>
  <link rel="stylesheet" href="../asset/contact.css">
  <script src="../asset/js/contact.js" defer></script>
</head>
<body>

<div class="container">
  <h2>Contact Us</h2>

  <?php if (!empty($msg)): ?>
    <div class="msg"><?= $msg ?></div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <?php foreach ($errors as $error): ?>
      <div class="error"><?= $error ?></div>
    <?php endforeach; ?>
  <?php endif; ?>

  <form name="contactForm" method="post" onsubmit="return validateForm();">
    <label>Your Name *</label>
    <input type="text" name="name" required>

    <label>Your Email *</label>
    <input type="email" name="email" required>

    <label>Subject *</label>
    <input type="text" name="subject" required>

    <label>Your Message *</label>
    <textarea name="message" rows="5" required></textarea>

    <label>CAPTCHA: What is <strong><?= $_SESSION['captcha_code'] ?></strong>?</label>
    <input type="text" name="captcha" required>

    <button type="submit">Send Message</button>
  </form>
</div>

</body>
</html>
