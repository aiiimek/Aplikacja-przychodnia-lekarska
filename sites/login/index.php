<?php
require_once __DIR__ . '/../../SaySoft/master.php';
?>
<!doctype html>
<html lang="pl">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Logowanie — Przychodnia</title>
  <link href="../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
  <link href="../../assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../assets/css/custom.css" rel="stylesheet">
</head>

<body class="bg-light">

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-sm-11 col-md-10 col-lg-8 col-xl-7">

        <div class="card auth-card shadow-sm">
          <div class="row no-gutters">
            <div class="col-md-5 auth-left d-flex flex-column justify-content-center align-items-start p-5">
              <div class="w-100 text-center mb-3">
                <h3 class="brand">Lekario</h3>
                <p class="mb-1">Bezpieczny dostęp dla pacjentów i personelu</p>
                <small class="text-white-50">Zaloguj się, aby uzyskać dostęp do terminarza, wyników i dokumentacji.</small>
              </div>
              <div class="w-100 text-center mt-auto">
                <img src="../../assets/img/undraw_profile.svg" alt="logo" class="img-fluid" style="max-width:120px; opacity:.95">
              </div>
            </div>
            <div class="col-md-7">
              <div class="card-body p-5">
                <h3 class="card-title text-center mb-3">Logowanie do portalu pacjenta</h3>

                <!-- Alerty z JS -->
                <div id="loginAlert"></div>

                <form id="loginForm" novalidate>
                  <div class="form-group">
                    <label for="username">Nazwa użytkownika</label>
                    <input id="username" name="username" type="text" class="form-control form-control-lg" placeholder="Login" required>
                  </div>

                  <div class="form-group">
                    <label for="password">Hasło</label>
                    <input id="password" name="password" type="password" class="form-control form-control-lg" placeholder="Hasło" required minlength="6">
                  </div>

                  <div class="form-row align-items-center mb-3">
                    <div class="col">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                        <label class="custom-control-label" for="remember">Zapamiętaj mnie</label>
                      </div>
                    </div>
                    <div class="col text-right">
                      <a href="../../forgot-password.html" class="small">Nie pamiętasz hasła?</a>
                    </div>
                  </div>

                  <button type="button" class="btn btn-primary btn-block btn-lg" onclick="Login.Login()">Zaloguj się</button>
                </form>

                <hr class="my-4">
                <p class="text-center small mb-0">Nie masz konta? <a href="../register/">Zarejestruj się</a></p>

              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="../../assets/vendor/jquery/jquery.min.js"></script>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/js/base/login.js"></script> <!-- Twój Login.Login() JS -->

</body>
</html>
