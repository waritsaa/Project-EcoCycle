let signupName = document.getElementById("signupName");
let signupEmail = document.getElementById("signupEmail");
let signupPassword = document.getElementById("signupPassword");

let loginEmail = document.getElementById("loginEmail");
let loginPassword = document.getElementById("loginPassword");

let btnLogin = document.getElementById("btnLogin");
let btnSignup = document.getElementById("btnSignup");

let loginForm = document.getElementById("loginForm");
let signupForm = document.getElementById("signupForm");

let loginMessage = document.getElementById("loginMessage");
let signupMessage = document.getElementById("signupMessage");

btnLogin.onclick = function () {
  btnLogin.classList.add("active");
  btnSignup.classList.remove("active");

  loginForm.classList.add("active");
  signupForm.classList.remove("active");

  loginMessage.textContent = "";
  signupMessage.textContent = "";
};

btnSignup.onclick = function () {
  btnSignup.classList.add("active");
  btnLogin.classList.remove("active");

  signupForm.classList.add("active");
  loginForm.classList.remove("active");

  loginMessage.textContent = "";
  signupMessage.textContent = "";
};

signupForm.addEventListener("submit", function(e){
  e.preventDefault();

  fetch("signUp.php", {
  method: "POST",
  headers: {
    "Content-Type": "application/x-www-form-urlencoded"
  },
  body: new URLSearchParams({
    name: signupName.value,
    email: signupEmail.value,
    password: signupPassword.value
  })
  })
  .then(res => res.text())
  .then(data => {
    signupMessage.textContent = data;
  });
});

loginForm.addEventListener("submit", function(e){
  e.preventDefault();

  fetch("login.php", {
    method: "POST",
    body: new URLSearchParams({
      email: loginEmail.value,
      password: loginPassword.value
    })
  })
  .then(res => res.text())
  .then(data => {
    if(data.trim() === "sukses"){
      window.location = "dashboard2.php";
    } else {
      loginMessage.textContent = "Login gagal";
    }
  });
});