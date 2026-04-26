<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

// Collect and trim inputs
$student_id = trim($_POST['student_id'] ?? '');
$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$email      = trim($_POST['email'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$program    = trim($_POST['program'] ?? '');
$year_level = isset($_POST['year_level']) ? (int)$_POST['year_level'] : 0;
$notes      = trim($_POST['notes'] ?? '');

$errors = [];

// Server-side validation
if ($student_id === '') $errors[] = 'Student ID is required.';
if ($first_name === '') $errors[] = 'First name is required.';
if ($last_name === '') $errors[] = 'Last name is required.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
if ($program === '') $errors[] = 'Program is required.';
if ($year_level < 1 || $year_level > 4) $errors[] = 'Year level must be between 1 and 4.';

if (!empty($errors)) {
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Errors</title><link rel="stylesheet" href="styles.css"></head><body><div class="container">';
    echo '<h2>There were errors with your submission</h2><ul>';
    foreach ($errors as $e) {
        echo '<li>' . htmlspecialchars($e) . '</li>';
    }
    echo '</ul><p><a href="register.php">Back to form</a></p></div></body></html>';
    exit;
}

// Insert using prepared statement
try {
    $stmt = $pdo->prepare('INSERT INTO students (student_id, first_name, last_name, email, phone, program, year_level, notes) VALUES (:student_id, :first_name, :last_name, :email, :phone, :program, :year_level, :notes)');
    $stmt->execute([
        'student_id' => $student_id,
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'email'      => $email,
        'phone'      => $phone ?: null,
        'program'    => $program,
        'year_level' => $year_level,
        'notes'      => $notes ?: null
    ]);

    // Redirect to list with success message
    header('Location: students.php?msg=added');
    exit;
} catch (PDOException $e) {
    // Handle duplicate student_id or other DB errors
    $msg = 'Database error: ' . htmlspecialchars($e->getMessage());
    if ($e->getCode() === '23000') {
        $msg = 'A record with that Student ID or email already exists.';
    }
    echo '<!doctype html><html><head><meta charset="utf-8"><title>Error</title><link rel="stylesheet" href="styles.css"></head><body><div class="container">';
    echo '<h2>Error</h2><p>' . $msg . '</p><p><a href="register.php">Back to form</a></p></div></body></html>';
    exit;
}
?>