<?php
require_once '../config.php';
require_login();
$user = current_user();
if($user['role'] !== 'faculty'){ header("Location: ../index.php"); exit; }
$pdo = pdo();

$subject = intval($_GET['subject'] ?? 0);
$subs = $pdo->prepare("SELECT * FROM subjects WHERE faculty_id = ?");
$subs->execute([$user['id']]);
$subs = $subs->fetchAll();

$students = [];
if($subject){
    // students in selected subject
    $students = $pdo->prepare("SELECT s.*, g.prelim,g.midterm,g.finals FROM students s LEFT JOIN grades g ON g.student_id=s.id AND g.subject_id=s.subject_id WHERE s.subject_id=? AND s.archived=0");
    $students->execute([$subject]);
    $students = $students->fetchAll();
}

// add activity
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_activity'])){
    $student_id = intval($_POST['student_id']);
    $subject_id = intval($_POST['subject_id']);
    $title = $_POST['title'];
    $score = floatval($_POST['score'] ?: 0);
    $pdo->prepare("INSERT INTO activities (student_id,subject_id,title,score) VALUES (?,?,?,?)")->execute([$student_id,$subject_id,$title,$score]);
    header("Location: activities.php?subject=".$subject_id);
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Activities - ClassFlow</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <?php include '../shared/left_nav.php'; ?>
  <main class="main-content">
    <div class="container py-4">
      <h3>Activities</h3>
      <form method="get" class="mb-3">
        <select name="subject" class="form-select w-auto d-inline">
          <option value="">-- Select subject --</option>
          <?php foreach($subs as $s): ?>
            <option value="<?php echo $s['id']?>" <?php echo $subject==$s['id'] ? 'selected' : '' ?>><?php echo htmlspecialchars($s['name'])?></option>
          <?php endforeach;?>
        </select>
        <button class="btn btn-outline-secondary">Open</button>
      </form>

      <?php if($subject): ?>
        <table class="table">
          <thead><tr><th>Pic</th><th>Lastname</th><th>Firstname</th><th>Actions</th></tr></thead>
          <tbody>
            <?php foreach($students as $st): ?>
              <tr>
                <td><img src="../<?php echo $st['avatar'] ?>" style="width:40px;height:40px;object-fit:cover;border-radius:6px;"></td>
                <td><?php echo htmlspecialchars($st['lastname']) ?></td>
                <td><?php echo htmlspecialchars($st['firstname']) ?></td>
                <td>
                  <!-- add activity modal trigger -->
                  <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#actModal<?php echo $st['id'] ?>">Add Activity</button>
                  <a class="btn btn-sm btn-outline-secondary" href="update_activities.php?student=<?php echo $st['id'] ?>&subject=<?php echo $subject ?>">Update</a>
                </td>
              </tr>

              <!-- modal -->
              <div class="modal fade" id="actModal<?php echo $st['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <form method="post" class="modal-content">
                    <input type="hidden" name="add_activity" value="1">
                    <input type="hidden" name="student_id" value="<?php echo $st['id'] ?>">
                    <input type="hidden" name="subject_id" value="<?php echo $subject ?>">
                    <div class="modal-header"><h5 class="modal-title">Add Activity for <?php echo htmlspecialchars($st['firstname']) ?></h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                      <div class="mb-2"><label>Title</label><input name="title" class="form-control"></div>
                      <div class="mb-2"><label>Score</label><input name="score" class="form-control" type="number" step="0.01"></div>
                    </div>
                    <div class="modal-footer"><button class="btn btn-orange">Add</button></div>
                  </form>
                </div>
              </div>

            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>