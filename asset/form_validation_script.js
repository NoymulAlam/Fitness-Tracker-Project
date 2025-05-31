function validateName() {
  const name = document.getElementById('name').value.trim();
  document.getElementById('nameError').style.display = name.length < 3 ? 'block' : 'none';
  return name.length >= 3;
}

function validateEmail() {
  const email = document.getElementById('email').value.trim();
  const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  document.getElementById('emailError').style.display = !valid ? 'block' : 'none';
  return valid;
}

function validateAge() {
  const age = parseInt(document.getElementById('age').value);
  const valid = age > 0;
  document.getElementById('ageError').style.display = !valid ? 'block' : 'none';
  return valid;
}

function validateForm() {
  return validateName() && validateEmail() && validateAge();
}
