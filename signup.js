
    function validateForm() {
      const username = document.getElementById("username").value;
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirmPassword").value;
      const errorMsg = document.getElementById("errorMsg");

      if (password.length < 6) {
        errorMsg.textContent = "Password must be at least 6 characters.";
        return false;
      }

      if (password !== confirmPassword) {
        errorMsg.textContent = "Passwords do not match. Please put same passwords.";
        return false;
      }

      // You can add more validation here if needed

      alert("Signup successful!");
      return true; // Allow form submission
    }

