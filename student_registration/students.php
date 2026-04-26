<?php
require 'config.php';

// Read filters
$search = trim((string)($_GET['search'] ?? ''));
$program = trim((string)($_GET['program'] ?? ''));
$year_level = trim((string)($_GET['year_level'] ?? ''));

$where = [];
$params = [];

if ($search !== '') {
    $where[] = '(student_id LIKE :s OR first_name LIKE :s OR last_name LIKE :s OR email LIKE :s)';
    $params['s'] = '%' . $search . '%';
}
if ($program !== '') {
    $where[] = 'program = :program';
    $params['program'] = $program;
}
if ($year_level !== '') {
    $where[] = 'year_level = :year';
    $params['year'] = (int)$year_level;
}

$sql = 'SELECT * FROM students';
if (!empty($where)) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY created_at DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Students List</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
  <h1>Students</h1>
  <?php if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>
    <p class="success">Student successfully added.</p>
  <?php endif; ?>

  <form method="get" class="filters">
    <input type="search" name="search" placeholder="Search by ID, name or email" value="<?php echo htmlspecialchars($search); ?>">
    <input type="text" name="program" placeholder="Program" value="<?php echo htmlspecialchars($program); ?>">
    <select name="year_level">
      <option value="">Any year</option>
      <option value="1" <?php echo $year_level==='1'?'selected':''; ?>>1</option>
      <option value="2" <?php echo $year_level==='2'?'selected':''; ?>>2</option>
      <option value="3" <?php echo $year_level==='3'?'selected':''; ?>>3</option>
      <option value="4" <?php echo $year_level==='4'?'selected':''; ?>>4</option>
    </select>
    <button type="submit">Filter</button>
    <a class="btn-link" href="register.php">Register new student</a>
  </form>

  <table>
    <thead>
      <tr>
        <th>Student ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Program</th>
        <th>Year</th>
        <th>Notes</th>
        <th>Registered</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($students)): ?>
        <tr><td colspan="8">No records found.</td></tr>
      <?php else: ?>
        <?php foreach ($students as $row): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['student_id']); ?></td>
            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['phone']); ?></td>
            <td><?php echo htmlspecialchars($row['program']); ?></td>
            <td><?php echo htmlspecialchars($row['year_level']); ?></td>
            <td><?php echo nl2br(htmlspecialchars($row['notes'])); ?></td>
            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>