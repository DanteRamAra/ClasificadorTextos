const form = document.getElementById("registro");
const passwordInput = document.getElementById("pass");
const passwordError = document.getElementById("password-error");

// Validación en tiempo real (cuando el usuario escribe)
passwordInput.addEventListener("input", () => {
  if (passwordInput.value.length < 8) {
    passwordError.style.display = "block";
  } else {
    passwordError.style.display = "none";
  }
});

// Validación al enviar el formulario
form.addEventListener("submit", (e) => {
  if (passwordInput.value.length < 8) {
    e.preventDefault(); // Evita que se envíe
    passwordError.style.display = "block";
    passwordInput.focus(); // Enfoca el campo
  }
});