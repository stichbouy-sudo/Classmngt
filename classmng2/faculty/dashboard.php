<?php
require_once '../config.php';
require_login();
$user = current_user();
if($user['role'] !== 'faculty'){
    header("Location: index.php"); exit;
}
$pdo = pdo();

// fetch subjects for faculty
$subjects = $pdo->prepare("SELECT * FROM subjects WHERE faculty_id = ? ORDER BY created_at DESC");
$subjects->execute([$user['id']]);
$subs = $subjects->fetchAll();

// handle add subject
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_subject'])){
    $name = $_POST['name'];
    $code = $_POST['code'];
    $schedule = $_POST['schedule'];
    $stmt = $pdo->prepare("INSERT INTO subjects (faculty_id,name,code,schedule) VALUES (?,?,?,?)");
    $stmt->execute([$user['id'],$name,$code,$schedule]);
    header("Location: dashboard.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Faculty Dashboard - ClassFlow</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="/classflow-favicon.ico">
  <link rel="stylesheet" href="../assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <?php include '../shared/left_nav.php'; ?>
  <main class="main-content">
    <div class="container-fluid py-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h2 style="color:orange;"><b></b>ClassFlow</b></h2> 
          <h5 class="text-muted"><?php echo htmlspecialchars($user['fullname']); ?></h5>
        </div>
        <div>
          <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addSubModal">Add Subject</button>
        </div>
      </div>

      <!-- subjects list -->
      <div class="row g-3">
        <?php if(empty($subs)): ?>
          <div class="col-12">
            <div class="card p-4 text-center">
              <p class="mb-0">No subjects yet. Add one to begin.</p>
            </div>
          </div>
        <?php endif; ?>
        <?php foreach($subs as $s): ?>
          <div class="col-md-4">
            <div class="card subject-card h-100" style="border-top:3px solid #ff8a00;">
              <div class="card-body d-flex flex-column">
                <h5><?php echo htmlspecialchars($s['name']); ?></h5>
                <div class="text-muted mb-2 small"><?php echo htmlspecialchars($s['code']); ?> • <?php echo htmlspecialchars($s['schedule']); ?></div>
                <div class="mt-auto d-flex gap-2">
                  <a href="subject.php?id=<?php echo $s['id'] ?>" class="btn btn-orange btn-sm">Open</a>
                  <a href="edit_subject.php?id=<?php echo $s['id'] ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </main>

  <!-- Add Subject Modal -->
  <div class="modal fade" id="addSubModal" tabindex="-1">
    <div class="modal-dialog">
      <form method="post" class="modal-content">
        <input type="hidden" name="add_subject" value="1">
        <div class="modal-header">
          <h5 class="modal-title">Add Subject</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label>Subject name</label>
            <input name="name" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Code</label>
            <input name="code" class="form-control">
          </div>
          <div class="mb-2">
            <label>Schedule (e.g. M,W,F - 5:00-7:00)</label>
            <input name="schedule" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-orange">Add Subject</button>
        </div>
      </form>
    </div>
  </div>

</body>
</html>