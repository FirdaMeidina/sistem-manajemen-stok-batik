// ====== TOGGLE SIDEBAR ======
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  sidebar.classList.toggle("d-none");
}

// ====== PASSWORD VISIBILITY ======
function togglePassword(id, iconId) {
  const pw = document.getElementById(id);
  const icon = document.getElementById(iconId);
  pw.type = pw.type === "password" ? "text" : "password";
  icon.classList.toggle("fa-eye-slash");
}

// ====== TOAST / ALERT AUTO HIDE ======
document.addEventListener("DOMContentLoaded", () => {
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((a) => {
    setTimeout(() => {
      a.style.opacity = "0";
      setTimeout(() => a.remove(), 600);
    }, 3000);
  });
});

// ====== SMOOTH SCROLL TO TOP ======
const btnTop = document.createElement("button");
btnTop.innerHTML = "⬆";
btnTop.classList.add("btn", "btn-batik", "position-fixed", "bottom-3", "end-3");
btnTop.style.display = "none";
btnTop.style.zIndex = "999";
document.body.appendChild(btnTop);

window.onscroll = () => {
  btnTop.style.display = window.scrollY > 200 ? "block" : "none";
};
btnTop.onclick = () => window.scrollTo({ top: 0, behavior: "smooth" });
