function showForm(name) {
  const forms = ['loginFormSection', 'signupFormSection', 'forgotFormSection', 'resetFormSection'];
  forms.forEach(id => {
    const el = document.getElementById(id);
    if (el) el.style.display = 'none';
  });

  const target = name + 'FormSection';
  const title = {
    login: 'Login',
    signup: 'Signup',
    forgot: 'Forgot Password',
    reset: 'Reset Password'
  };

  const form = document.getElementById(target);
  if (form) {
    form.style.display = 'block';
    document.getElementById('formTitle').textContent = title[name];
  }
}

window.onload = () => showForm('login');
