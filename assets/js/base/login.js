// document.addEventListener('DOMContentLoaded', function () {
//   const form = document.getElementById('registerForm');
//   if (!form) return;

//   const alertBox = document.getElementById('registerAlert');
//   const submitBtn = form.querySelector('button[type="submit"]');

//   function showAlert(message, type = 'danger'){
//     if(!alertBox) return;
//     alertBox.innerHTML = `<div class="alert alert-${type}" role="alert">${message}</div>`;
//   }

//   form.addEventListener('submit', function (e) {
//     e.preventDefault();

//     const data = {
//       firstName: document.getElementById('firstName')?.value || null,
//       lastName: document.getElementById('lastName')?.value || null,
//       username: document.getElementById('username')?.value || '',
//       email: document.getElementById('email')?.value || '',
//       password: document.getElementById('password')?.value || '',
//       password2: document.getElementById('password2')?.value || '',
//       acceptPrivacy: document.getElementById('flexCheckDefault')?.checked ? 1 : 0
//     };

//     // basic client-side checks
//     if (!data.username || data.username.length < 3) {
//       showAlert('Nazwa użytkownika musi mieć minimum 3 znaki.');
//       return;
//     }
//     if (!data.email) {
//       showAlert('Podaj poprawny adres e‑mail.');
//       return;
//     }
//     if (!data.password || data.password.length < 6) {
//       showAlert('Hasło musi mieć minimum 6 znaków.');
//       return;
//     }
//     if (data.password !== data.password2) {
//       showAlert('Hasła nie są identyczne.');
//       return;
//     }
//     if (!data.acceptPrivacy) {
//       showAlert('Musisz zaakceptować politykę prywatności.');
//       return;
//     }

//     // disable submit
//     if (submitBtn) submitBtn.disabled = true;
//     showAlert('Wysyłanie danych...', 'info');

//     fetch('register.php', {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/json',
//         'Accept': 'application/json'
//       },
//       body: JSON.stringify(data),
//       credentials: 'same-origin'
//     }).then(async function (res) {
//       let json;
//       try { json = await res.json(); } catch (err) { throw new Error('Nieprawidłowa odpowiedź serwera'); }

//       if (res.ok && json && json.success) {
//         showAlert('Rejestracja zakończona pomyślnie. Przekierowanie...', 'success');
//         setTimeout(function(){ window.location.href = '../login/'; }, 1200);
//       } else {
//         const errs = (json && json.errors) ? json.errors : [json && json.message ? json.message : 'Błąd serwera'];
//         showAlert('<ul class="mb-0">' + errs.map(e => '<li>'+escapeHtml(String(e))+'</li>').join('') + '</ul>', 'danger');
//       }
//     }).catch(function (err) {
//       showAlert('Błąd sieci lub serwera: ' + (err.message || err));
//     }).finally(function (){
//       if (submitBtn) submitBtn.disabled = false;
//     });
//   });

//   function escapeHtml(str) {
//     return str.replace(/[&<>"'`]/g, function (m) { return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;','`':'&#x60;'})[m]; });
//   }

// });
var Login = {
    registerAlert: function (span, message, type = 'danger') {
        if (!span) return;
        span.innerHTML = `<div class="alert alert-${type}" role="alert">${message}</div>`;
    },

    Register: function () {
        const alertBox = document.getElementById('registerAlert');

        const fields = ['firstName', 'lastName', 'username', 'email', 'password', 'password2', 'agr'];
        const dataArr = {};

        fields.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            dataArr[id] = (el.type === 'checkbox') ? (el.checked ? 1 : 0) : el.value.trim();
        });

        // walidacja -UWAGA - NIE WALIDUJEMY JSEM - UŻYWAMY PHPA
        if (!dataArr.firstName || dataArr.firstName.length < 3)
            return Login.registerAlert(alertBox, 'Imię musi mieć minimum 3 znaki.');

        if (!dataArr.username || dataArr.username.length < 3)
            return Login.registerAlert(alertBox, 'Nazwa użytkownika musi mieć minimum 3 znaki.');

        if (!dataArr.password || dataArr.password.length < 6)
            return Login.registerAlert(alertBox, 'Hasło musi mieć minimum 6 znaków.');

        if (dataArr.password !== dataArr.password2)
            return Login.registerAlert(alertBox, 'Hasła nie są identyczne.');

        if (Number(dataArr.agr) !== 1)
            return Login.registerAlert(alertBox, 'Musisz zaakceptować politykę prywatności.');

        fetch('/Aplikacja-przychodnia-lekarska/sites/register/save.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'dataArr=' + encodeURIComponent(JSON.stringify(dataArr))
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.text();
        })
        .then(text => {
            try {
                const jsonStart = text.indexOf('{');
                const clean = jsonStart !== -1 ? text.substring(jsonStart) : text;
                const result = JSON.parse(clean);

                if (result.success) {
                    Login.registerAlert(alertBox, 'Rejestracja zakończona pomyślnie!', 'success');
                } else {
                    Login.registerAlert(alertBox, result.message || 'Błąd podczas rejestracji');
                }
            } catch (e) {
                console.error('Odpowiedź serwera:', text);
                throw new Error('Nieprawidłowa odpowiedź serwera');
            }
        })
        .catch(err => {
            Login.registerAlert(alertBox, 'Błąd: ' + err.message);
            console.error('Fetch error:', err);
        });
    },

    Login: function () {
        const alertBox = document.getElementById('loginAlert');

        const usernameEl = document.getElementById('username');
        const passwordEl = document.getElementById('password');

        if (!usernameEl || !passwordEl) return;

        const dataArr = {
            username: usernameEl.value.trim(),
            password: passwordEl.value.trim()
        };

        // walidacja
        if (!dataArr.username || dataArr.username.length < 3)
            return Login.registerAlert(alertBox, 'Nazwa użytkownika musi mieć minimum 3 znaki.');

        if (!dataArr.password || dataArr.password.length < 6)
            return Login.registerAlert(alertBox, 'Hasło musi mieć minimum 6 znaków.');

        fetch('/Aplikacja-przychodnia-lekarska/sites/login/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'dataArr=' + encodeURIComponent(JSON.stringify(dataArr))
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.text();
        })
        .then(text => {
            try {
                const jsonStart = text.indexOf('{');
                const clean = jsonStart !== -1 ? text.substring(jsonStart) : text;
                const result = JSON.parse(clean);

                if (result.success) {
                    // przekierowanie po zalogowaniu
                    window.location.href = 'http://localhost/Aplikacja-przychodnia-lekarska/sites/dashboard/';
                } else {
                    Login.registerAlert(alertBox, result.message || 'Błąd logowania');
                }
            } catch (e) {
                console.error('Odpowiedź serwera:', text);
                throw new Error('Nieprawidłowa odpowiedź serwera');
            }
        })
        .catch(err => {
            Login.registerAlert(alertBox, 'Błąd: ' + err.message);
            console.error('Fetch error:', err);
        });
    }
};



