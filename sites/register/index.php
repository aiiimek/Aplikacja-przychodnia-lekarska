<?php
require_once __DIR__ . '/../../SaySoft/master.php';

login_init();
$errors = [];
$email = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $password2 = $_POST['password2'] ?? '';
  $firstName = trim($_POST['firstName'] ?? '') ?: null;
  $lastName = trim($_POST['lastName'] ?? '') ?: null;

  if ($password !== $password2) {
    $errors[] = 'Hasła nie są identyczne.';
  } else {
    $uid = register_user($username, $email, $password, $errors, $firstName, $lastName);
    if ($uid) {
      header('Location: ../login/');
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Rejestracja — Lekario</title>

    <link href="../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
    <link href="../../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card auth-card shadow-sm my-5">
          <div class="row no-gutters">
            <div class="col-md-5 auth-left d-flex flex-column justify-content-center align-items-start p-5">
              <div class="w-100 text-center mb-3">
                <h3 class="brand">Lekario</h3>
                <p class="mb-1">Zarejestruj konto pacjenta</p>
                <small class="text-white-50">Utwórz konto, aby zarządzać wizytami i dokumentacją.</small>
              </div>
              <div class="w-100 text-center mt-auto">
                <img src="../../assets/img/undraw_profile.svg" alt="logo" class="img-fluid" style="max-width:120px; opacity:.95">
              </div>
            </div>
            <div class="col-md-7">
              <div class="card-body p-5">
                <h3 class="card-title text-center mb-3">Rejestracja</h3>

                <?php if (!empty($errors)): ?>
                  <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div>
                <?php endif; ?>

                <form class="" method="post" action="" id="registerForm">
                  <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <input type="text" class="form-control form-control-user" id="firstName" name="firstName" placeholder="Imię" value="<?php echo htmlspecialchars($firstName ?? ''); ?>">
                    </div>
                    <div class="col-sm-6">
                      <input type="text" class="form-control form-control-user" id="lastName" name="lastName" placeholder="Nazwisko" value="<?php echo htmlspecialchars($lastName ?? ''); ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="Nazwa użytkownika" value="<?php echo htmlspecialchars($username); ?>" required>
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Adres e‑mail" value="<?php echo htmlspecialchars($email); ?>" required>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                      <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Hasło" required minlength="6">
                    </div>
                    <div class="col-sm-6">
                      <input type="password" class="form-control form-control-user" id="password2" name="password2" placeholder="Powtórz hasło" required minlength="6">
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary btn-user btn-block">Zarejestruj konto</button>
                </form>

                <hr class="my-4">
                <p class="text-center small mb-0">Masz już konto? <a href="../login/">Zaloguj się</a></p>

              </div>
            </div>
          </div>
        </div>

    </div>

    <script src="../../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>

    <script>
    (function(){
      var form = document.getElementById('registerForm');
      form.addEventListener('submit', function(e){
        var p1 = document.getElementById('password').value;
        var p2 = document.getElementById('password2').value;
        if (p1 !== p2) {
          e.preventDefault();
          alert('Hasła nie są identyczne');
        }
      });
    })();
    </script>

</body>

</html>
