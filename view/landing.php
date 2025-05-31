<?php
require_once '../model/landing_database.php';
require_once '../controller/ctaController.php';

?>
<!DOCTYPE html>
<html>
<head>
  <title>Fitness Tracker - Landing Page</title>
  <link rel="stylesheet" href="../asset/landing.css">
</head>
<body>

<!-- Navigation -->
<nav>
  <div>Fitness Tracker</div>
  <div>
    <a href="#features">Features</a>
    <a href="#testimonials">Testimonials</a>
    <a href="#about">About</a>
    <a href="../view/authView.php" target="_blank">Signup</a>
  </div>
</nav>

<!-- Landing Hero -->
<header>
  <div>
    <h1>Track Your Fitness. Transform Your Life.</h1>
    <form method="post">
    <button class="cta" type="button" onclick="window.open('../view/authView.php', '_blank')">Get Started</button>

    </form>
  </div>
</header>

<!-- Features Section -->
<section id="features" class="features">
  <h2>Features</h2>
  <ul>
    <li>✅ Personalized workout tracking</li>
    <li>✅ Nutrition and meal logging</li>
    <li>✅ Progress tracking with charts</li>
    <li>✅ Goal setting and achievements</li>
  </ul>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="testimonials">
  <h2>What Our Users Say</h2>
  <div class="testimonial">
    "This changed my fitness journey. The 'Nutrition logging' is a game-changer feature!" - <strong>Fahad Hassan Fahim</strong>
  </div>
  <div class="testimonial">
    "I love the workout log and daily reminders. Super easy to stay on track!" - <strong>Faisal Ahmed</strong>
  </div>
</section>

<!-- About Section -->
<section id="about">
  <h2>About Fitness Tracker</h2>
  <p>Fitness Tracker is your personal fitness assistant. Whether you're a beginner or a pro, our tools help you stay consistent and hit your goals with smart tracking, personalized plans, and real-time feedback.</p>
</section>

<!-- Signup CTA -->
<section id="signup">
  <h2>Ready to Start?</h2>
  <p>Sign up now and take the first step towards a healthier you.</p>
  <form method="post">
    <button class="cta" type="submit" name="cta" value="Signup">Signup Now</button>
  </form>
</section>

<!-- Footer -->
<footer>
  &copy; <?= date('Y') ?> Fitness Tracker. All rights reserved.
</footer>

</body>
</html>
