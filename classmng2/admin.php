<?php
require_once 'config.php';
require_login();
$user = current_user();
if($user['role'] !== 'admin'){
    header("Location: index.php"); exit;
}
$pdo = pdo();

// handle create faculty
$msg = '';
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_faculty'])){
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $department = trim($_POST['department']);
    $password = password_hash($_POST['password'] ?: 'password123', PASSWORD_DEFAULT);
    // create user
    $stmt = $pdo->prepare("
    INSERT INTO users (username, password, fullname, role, department)
    VALUES (?, ?, ?, 'faculty', ?)
");

try {
    $stmt->execute([$username, $password, $fullname, $department]);
    $msg = "Faculty account created. An empty account has been prepared.";
} catch (Exception $e) {
    $msg = "Error: " . $e->getMessage();
}
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - ClassFlow</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <?php include 'shared/left_nav.php'; ?>
  <main class="main-content">
    <div class="container-fluid py-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Admin Panel — ClassFlow</h2>
        <div>
          <a href="backup.php" class="btn btn-outline-secondary">Backup CSV</a>
          <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>
      </div>

      <?php if($msg): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($msg) ?></div>
      <?php endif; ?>

      <div class="card mb-4">
        <div class="card-body">
          <h5>Create Faculty Account</h5>
          <form method="post" class="row g-3">
            <input type="hidden" name="create_faculty" value="1">
            <div class="col-md-4">
              <label class="form-label">Username</label>
              <input name="username" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Full name</label>
              <input name="fullname" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label">Department</label>
              <input name="department" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label">Password (optional)</label>
              <input name="password" class="form-control" placeholder="Defaults to password123">
            </div>
            <div class="col-12">
              <button class="btn btn-orange">Create Faculty</button>
            </div>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <h5>Faculty accounts</h5>
          <table class="table">
            <thead><tr><th>Username</th><th>Fullname</th><th>Department</th><th>Action</th><th>Created</th></tr></thead>
            <tbody>
            <?php
              $rows = $pdo->query("SELECT id,username,fullname,department,created_at FROM users WHERE role='faculty' ORDER BY created_at DESC")->fetchAll();
              foreach ($rows as $r) {
               echo "<tr>
               <td>" . htmlspecialchars($r['username']) . "</td>
               <td>" . htmlspecialchars($r['fullname']) . "</td>
               <td>" . htmlspecialchars($r['department']) . "</td>

             <td>
            <a href='delete_user.php?id=".$r['id']."'
               class='btn btn-danger btn-sm'
               onclick=\"return confirm('Are you sure you want to delete this user?');\">
               Delete
            </a>
        </td>

        <td>" . $r['created_at'] . "</td>
    </tr>";
}
            ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </main>
</body>
</html>