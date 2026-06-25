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

let loginRoleGrid = document.getElementById("loginRoleGrid");
let signupRoleGrid = document.getElementById("signupRoleGrid");

// ===== Role card selector behavior =====
function setupRoleGrid(grid) {
  if (!grid) return;
  const cards = grid.querySelectorAll(".role-card");
  cards.forEach(card => {
    card.addEventListener("click", function () {
      cards.forEach(c => c.classList.remove("selected"));
      card.classList.add("selected");
      const input = card.querySelector("input[type=radio]");
      if (input) input.checked = true;
    });
  });
}
setupRoleGrid(loginRoleGrid);
setupRoleGrid(signupRoleGrid);

function getSelectedRole(grid) {
  const checked = grid.querySelector("input[type=radio]:checked");
  return checked ? checked.value : "masyarakat";
}

function resetRoleGrid(grid) {
  grid.querySelectorAll(".role-card").forEach(c => c.classList.remove("selected"));
  const def = grid.querySelector('.role-card[data-role="masyarakat"]');
  def.classList.add("selected");
  def.querySelector("input").checked = true;
}

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

signupForm.addEventListener("submit", function (e) {
  e.preventDefault();

  signupMessage.textContent = "Memproses...";
  signupMessage.className = "";

  const role = getSelectedRole(signupRoleGrid);

  fetch("actions/signUp.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: new URLSearchParams({
      name: signupName.value,
      email: signupEmail.value,
      password: signupPassword.value,
      role: role
    })
  })
    .then(res => res.text())
    .then(data => {
      data = data.trim();
      if (data === "Daftar berhasil") {
        signupMessage.textContent = "Daftar berhasil! Silakan masuk.";
        signupMessage.className = "msg-success";
        signupForm.reset();
        resetRoleGrid(signupRoleGrid);
        setTimeout(() => btnLogin.click(), 1200);
      } else {
        signupMessage.textContent = data;
        signupMessage.className = "msg-error";
      }
    })
    .catch(() => {
      signupMessage.textContent = "Terjadi kesalahan, coba lagi.";
      signupMessage.className = "msg-error";
    });
});

loginForm.addEventListener("submit", function (e) {
  e.preventDefault();

  loginMessage.textContent = "Memproses...";
  loginMessage.className = "";

  const role = getSelectedRole(loginRoleGrid);

  fetch("actions/login.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: new URLSearchParams({
      email: loginEmail.value,
      password: loginPassword.value,
      role: role
    })
  })
    .then(res => res.text())
    .then(data => {
      data = data.trim();
      if (data.startsWith("sukses")) {
        const target = data.split("|")[1] || "dashboard2.php";
        loginMessage.textContent = "Berhasil masuk, mengalihkan...";
        loginMessage.className = "msg-success";
        window.location = target;
      } else if (data === "role_mismatch") {
        loginMessage.textContent = "Akun ini tidak terdaftar sebagai role yang dipilih.";
        loginMessage.className = "msg-error";
      } else {
        loginMessage.textContent = "Email atau password salah";
        loginMessage.className = "msg-error";
      }
    })
    .catch(() => {
      loginMessage.textContent = "Terjadi kesalahan, coba lagi.";
      loginMessage.className = "msg-error";
    });
});

navigator.geolocation.getCurrentPosition(function(position){

    document.getElementById("latitude").value =
        position.coords.latitude;

    document.getElementById("longitude").value =
        position.coords.longitude;

});
