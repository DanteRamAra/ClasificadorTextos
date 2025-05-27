const form = document.getElementById("registro");
const passwordInput = document.getElementById("pass");
const passwordError =document.getElementById("password-error");
const nombre=document.getElementById("nombre");
const nomError=document.getElementById("nombre_error");

nombre.addEventListener("input",()=>{
  const soloLetrasYEspacios=/^(?!.*\d)[A-Za-z\s]+$/
  if (!soloLetrasYEspacios.test(nombre.value)) {
    nomError.style.display = "block";

  } else {
    nomError.style.display = "none";
  }
})

form.addEventListener("submit",(e)=>{
  const soloLetrasYEspaciosSubmit=/^(?!.*\d)[A-Za-z\s]+$/
  if (!soloLetrasYEspaciosSubmit.test(nombre.value)) {
  e.preventDefault();
  passwordError.style.display = "block";
    passwordInput.focus(); // Enfoca el campo
  } 
})
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