<?php
require_once __DIR__ . '/../../SaySoft/master.php';

login_init();
 $errors = [];
 $login = '';
 $password = '';

// field-oriented error buckets (always defined for templates)
 $fieldErrors = ['login' => [], 'password' => [], 'general' => []];

if (isset($_SESSION['user_id'])) {
  header('Location: ../../index.php');
  exit;
}

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = trim($_POST['login'] ?? '');
  $password = $_POST['password'] ?? '';
  $rememberFlag = !empty($_POST['remember']);

  // pass fieldErrors by reference so attempt_login can fill them
  if (attempt_login($login, $password, $errors, $fieldErrors, $rememberFlag)) {
    header('Location: ../../index.php');
    exit;
  }
  // also write a developer-friendly copy to PHP error log for quick debugging
  if (!empty($errors)) {
    error_log('[sites/login/index.php] Login errors: ' . implode(' | ', $errors));
  }
}

// helper CSS classes for Bootstrap invalid state
$loginInvalidClass = !empty($fieldErrors['login']) ? ' is-invalid' : '';
$passwordInvalidClass = !empty($fieldErrors['password']) ? ' is-invalid' : '';
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

                <?php if (!empty($fieldErrors['general']) || !empty($fieldErrors['email']) || !empty($fieldErrors['password'])): ?>
                  <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                      <?php foreach (array_merge($fieldErrors['general'], $fieldErrors['email'], $fieldErrors['password']) as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                <?php endif; ?>

                <form id="loginForm" method="post" action="" novalidate>
                  <div class="form-group">
                    <label for="login">Nazwa użytkownika</label>
                    <input id="login" name="login" type="text" class="form-control form-control-lg<?php echo $loginInvalidClass; ?>" placeholder="twoj_login" value="<?php echo htmlspecialchars($login); ?>" required>
                    <?php if (!empty($fieldErrors['login'])): ?>
                      <div class="invalid-feedback">
                        <?php echo htmlspecialchars(implode(' ', $fieldErrors['login'])); ?>
                      </div>
                    <?php endif; ?>
                  </div>

                  <div class="form-group">
                    <label for="password">Hasło</label>
                    <input id="password" name="password" type="password" class="form-control form-control-lg<?php echo $passwordInvalidClass; ?>" placeholder="Twoje hasło" required minlength="6">
                    <?php if (!empty($fieldErrors['password'])): ?>
                      <div class="invalid-feedback">
                        <?php echo htmlspecialchars(implode(' ', $fieldErrors['password'])); ?>
                      </div>
                    <?php endif; ?>
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

                  <button type="submit" class="btn btn-primary btn-block btn-lg">Zaloguj się</button>
                </form>

                <hr class="my-4">
                <p class="text-center small mb-0">Nie masz konta? <a href="../register/">Zarejestruj się</a></p>

                <?php if (!empty($errors) || !empty($fieldErrors['general'])): ?>
                  <div class="card mt-4 border-danger">
                    <div class="card-header bg-danger text-white">Log błędów (debug)</div>
                    <div class="card-body small">
                      <strong>Pełna lista błędów:</strong>
                      <pre class="mb-2" style="white-space:pre-wrap; word-wrap:break-word;"><?php echo htmlspecialchars(json_encode($errors, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); ?></pre>

                      <strong>Błędy pola:</strong>
                      <pre class="mb-0" style="white-space:pre-wrap; word-wrap:break-word;"><?php echo htmlspecialchars(json_encode($fieldErrors, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); ?></pre>
                    </div>
                  </div>
                <?php endif; ?>

              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="../../assets/vendor/jquery/jquery.min.js"></script>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    // Prosta walidacja bootstrapowa po stronie klienta (ulepsza UX)
    (function(){
      'use strict'
      var form = document.getElementById('loginForm')
      form.addEventListener('submit', function(event){
        if(!form.checkValidity()){
          event.preventDefault(); event.stopPropagation();
        }
        form.classList.add('was-validated')
      }, false)
    })()
  </script>

</body>

</html>
