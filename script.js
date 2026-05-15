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

function getUsers() {
  let users = localStorage.getItem("users");
  return users ? JSON.parse(users) : [];
}

function saveUsers(users) {
  localStorage.setItem("users", JSON.stringify(users));
}

function tanya() {
  let jawaban = confirm("Apakah Anda setuju membuat akun EcoCycle?");

  if (jawaban) {
    return true;
  } else {
    alert("Pendaftaran dibatalkan");
    return false;
  }
}

signupForm.addEventListener("submit", function (e) {
  e.preventDefault();

  let name = document.getElementById("signupName").value;
  let email = document.getElementById("signupEmail").value;
  let password = document.getElementById("signupPassword").value;

  if (name === "" || email === "" || password === "") {
    signupMessage.style.color = "red";
    signupMessage.textContent = "Semua data harus diisi";
    return;
  }

  if (!tanya()) {
    signupMessage.style.color = "red";
    signupMessage.textContent = "Pendaftaran dibatalkan";
    return;
  }

  let users = getUsers();

  let exist = users.find(function (user) {
    return user.email === email;
  });

  if (exist) {
    signupMessage.style.color = "red";
    signupMessage.textContent = "Email sudah terdaftar";
    return;
  }

  users.push({
    name: name,
    email: email,
    password: password
  });

  saveUsers(users);

  signupMessage.style.color = "green";
  signupMessage.textContent = "Sign Up berhasil, silakan login";

  signupForm.reset();
});

loginForm.addEventListener("submit", function (e) {
  e.preventDefault();

  let email = document.getElementById("loginEmail").value;
  let password = document.getElementById("loginPassword").value;

  if (email === "" || password === "") {
    loginMessage.style.color = "red";
    loginMessage.textContent = "Email dan password harus diisi";
    return;
  }

  let users = getUsers();

  let user = users.find(function (u) {
    return u.email === email && u.password === password;
  });

  if (user) {
    loginMessage.style.color = "green";
    loginMessage.textContent = "Login berhasil";

    localStorage.setItem("currentUser", JSON.stringify(user));

    setTimeout(function () {
      window.location = "dashboard.html";
    }, 1000);
  } else {
    loginMessage.style.color = "red";
    loginMessage.textContent = "Email atau password salah";
  }
});
