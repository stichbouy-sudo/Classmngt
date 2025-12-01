<?php
require_once '../config.php';
require_login();
$user = current_user();
if($user['role'] !== 'faculty'){ header("Location: ../index.php"); exit; }
$pdo = pdo();
$subs = $pdo->prepare("SELECT * FROM subjects WHERE faculty_id = ?");
$subs->execute([$user['id']]);
$subs = $subs->fetchAll();

$selected = intval($_GET['subject'] ?? 0);
if($selected){
    $students = $pdo->prepare("SELECT * FROM students WHERE subject_id=? AND archived=0 ORDER BY lastname");
    $students->execute([$selected]);
    $students = $students->fetchAll();
}

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['toggle'])){
    $student_id = intval($_POST['student_id']);
    $subject_id = intval($_POST['subject_id']);
    $status = $_POST['status']; // present or absent
    $date = date('Y-m-d');
    // Prevent marking present if student is present in other class with overlapping schedule
    if($status === 'present'){
        // simplistic check: find other subjects where student exists and today's date times overlapping not enforced strictly
        $other = $pdo->prepare("SELECT subj.* FROM subjects subj JOIN students s ON s.subject_id = subj.id WHERE s.id = ? AND subj.id != ?");
        $other->execute([$student_id,$subject_id]);
        $otherSub = $other->fetch();
        if($otherSub){
            // for demo we assume overlap if schedules equal or both non-empty (imperfect but functional)
            if($otherSub['schedule'] && $otherSub['schedule'] === $pdo->query("SELECT schedule FROM subjects WHERE id=".$subject_id)->fetchColumn()){
                echo json_encode(['error' => "the student you're trying to mark as present is still on another class wait for the student's time to end to mark the students attendance present"]);
                exit;
            }
        }
    }
    // upsert: if attendance exists for today for this student+subject replace it
    $stmt = $pdo->prepare("SELECT id FROM attendance WHERE student_id=? AND subject_id=? AND date = ?");
    $stmt->execute([$student_id,$subject_id,$date]);
    $exists = $stmt->fetch();
    if($exists){
        $pdo->prepare("UPDATE attendance SET status=? WHERE id=?")->execute([$status,$exists['id']]);
    } else {
        $pdo->prepare("INSERT INTO attendance (student_id,subject_id,status,date) VALUES (?,?,?,?)")->execute([$student_id,$subject_id,$status,$date]);
    }
    echo json_encode(['ok'=>1]); exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Attendance - ClassFlow</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <?php include '../shared/left_nav.php'; ?>
  <main class="main-content">
    <div class="container py-4">
      <h3>Attendance</h3>
      <form method="get" class="mb-3">
        <select name="subject" class="form-select w-auto d-inline">
          <option value="">-- Select subject --</option>
          <?php foreach($subs as $s): ?>
            <option value="<?php echo $s['id']?>" <?php echo $selected==$s['id'] ? 'selected' : '' ?>><?php echo htmlspecialchars($s['name'].' • '.$s['schedule'])?></option>
          <?php endforeach;?>
        </select>
        <button class="btn btn-outline-secondary">Open</button>
      </form>

      <?php if(isset($students)): ?>
        <table class="table">
          <thead><tr><th></th><th>Lastname</th><th>Firstname</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
          <tbody>
            <?php foreach($students as $st): 
              $att = $pdo->prepare("SELECT status,date FROM attendance WHERE student_id=? AND subject_id=? AND date = ?");
              $att->execute([$st['id'],$selected,date('Y-m-d')]);
              $row = $att->fetch();
            ?>
              <tr id="r<?php echo $st['id'] ?>">
                <td><img src="../<?php echo $st['avatar'] ?>" style="width:40px;height:40px;object-fit:cover;border-radius:6px;"></td>
                <td><?php echo htmlspecialchars($st['lastname']) ?></td>
                <td><?php echo htmlspecialchars($st['firstname']) ?></td>
                <td class="status"><?php echo $row ? ucfirst($row['status']) : 'Not set' ?></td>
                <td class="date"><?php echo $row ? $row['date'] : date('Y-m-d') ?></td>
                <td>
                  <button class="btn btn-sm btn-success mark" data-id="<?php echo $st['id'] ?>" data-status="present">Present</button>
                  <button class="btn btn-sm btn-danger mark" data-id="<?php echo $st['id'] ?>" data-status="absent">Absent</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class="text-muted small">Note: Attendance resets each day (you can clear old dates by deleting records or rely on per-date entries).</div>
      <?php endif; ?>

    </div>
  </main>

<script>
$(function(){
  $('.mark').click(function(){
    var id = $(this).data('id');
    var status = $(this).data('status');
    $.post('', {toggle:1, student_id:id, subject_id:<?php echo $selected ?>, status:status}, function(resp){
      try{ var j = JSON.parse(resp); }catch(e){ alert('Server error'); return; }
      if(j.error){ alert(j.error); return; }
      $('#r'+id+' .status').text(status.charAt(0).toUpperCase()+status.slice(1));
      $('#r'+id+' .date').text('<?php echo date('Y-m-d') ?>');
    });
  });
});
</script>
</body>
</html>