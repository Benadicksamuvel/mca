/**
 * Client-side Validation for Register Form
 * Book Inventory System
 */

(function () {
  "use strict";

  const form = document.getElementById("registerForm");
  if (!form) return;

  /* ---- helpers ---- */
  function showError(fieldId, msg) {
    const input = document.getElementById(fieldId);
    const err   = document.getElementById(fieldId + "_error");
    if (input) input.classList.add("is-invalid");
    if (err)   { err.textContent = msg; err.classList.add("visible"); }
  }

  function clearError(fieldId) {
    const input = document.getElementById(fieldId);
    const err   = document.getElementById(fieldId + "_error");
    if (input) input.classList.remove("is-invalid");
    if (err)   { err.textContent = ""; err.classList.remove("visible"); }
  }

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function isValidPhone(phone) {
    return /^[0-9]{7,15}$/.test(phone);
  }

  /* ---- live feedback on blur ---- */
  const rules = {
    name:     v => v.trim() === "" ? "Name is required." : null,
    password: v => v.length < 4 ? "Password must be at least 4 characters." : null,
    phone:    v => !isValidPhone(v.trim()) ? "Enter a valid phone number (7-15 digits)." : null,
    address:  v => v.trim() === "" ? "Address is required." : null,
    email:    v => !isValidEmail(v.trim()) ? "Enter a valid email address." : null,
  };

  Object.keys(rules).forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    el.addEventListener("blur", () => {
      const msg = rules[id](el.value);
      msg ? showError(id, msg) : clearError(id);
    });
    el.addEventListener("input", () => {
      if (el.classList.contains("is-invalid")) {
        const msg = rules[id](el.value);
        msg ? showError(id, msg) : clearError(id);
      }
    });
  });

  /* ---- submit ---- */
  form.addEventListener("submit", function (e) {
    let valid = true;

    Object.keys(rules).forEach(id => {
      const el  = document.getElementById(id);
      if (!el) return;
      const msg = rules[id](el.value);
      if (msg) { showError(id, msg); valid = false; }
      else       clearError(id);
    });

    if (!valid) e.preventDefault();
  });
})();
