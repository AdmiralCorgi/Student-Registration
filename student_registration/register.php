<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Student Registration</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Student Registration</h1>
    <form action="save.php" method="post" novalidate>
      <label>Student ID*<input type="text" name="student_id" maxlength="20" required></label>
      <label>First name*<input type="text" name="first_name" maxlength="50" required></label>
      <label>Last name*<input type="text" name="last_name" maxlength="50" required></label>
      <label>Email*<input type="email" name="email" maxlength="100" required></label>
      <label>Phone<input type="tel" name="phone" maxlength="20"></label>
      <label>Program*<input type="text" name="program" maxlength="100" required></label>
      <label>Year level* 
        <select name="year_level" required>
          <option value="">Select</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
        </select>
      </label>
      <label>Notes<textarea name="notes" rows="4"></textarea></label>
      <div class="actions">
        <button type="submit">Register</button>
        <a class="btn-link" href="students.php">View Students</a>
      </div>
    </form>
  </div>
</body>
</html>