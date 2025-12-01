<?php
require_once 'config.php';
$pdo = pdo();
$err = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if($user && password_verify($password, $user['password'])){
        // store minimal user in session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'fullname' => $user['fullname'],
            'role' => $user['role'],
            'avatar' => $user['avatar'],
            'department' => $user['department']
        ];
        if($user['role'] === 'admin'){
            header("Location: admin.php");
        } else {
            header("Location: faculty/dashboard.php");
        }
        exit;
    } else {
        $err = 'Invalid username or password';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>ClassFlow - Login</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
  <div class="d-flex vh-100 align-items-center justify-content-center">
    <div class="card shadow-sm w-100" style="max-width:900px;">
      <div class="row g-0">
        <div class="col-md-6 d-none d-md-block bg-orange text-dark p-4">
          <img src="assets/img/classflow-logo.svg" alt="">
          <p class="lead">Modern student management — attendance, activities, grades.</p>
          <p class="small">Sign in to manage your classes and students now!.</p>
        </div>
        <div class="col-md-6 p-4">
          <h3 class="mb-3">Sign in</h3>
          <?php if($err): ?>
            <div class="alert alert-danger animate__animated animate__fadeIn"><?php echo htmlspecialchars($err) ?></div>
          <?php endif;?>
          <form method="post" class="mb-3">
            <div class="mb-2">
              <label class="form-label">Username</label>
              <input name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input name="password" type="password" class="form-control" required>
            </div>
            <button class="btn btn-orange w-100">Login</button>
          </form>
            <h6 class=''>Contact the admin to start you acount today</h6>
            <p class='small'>moralesoalden2@gmail.com</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>