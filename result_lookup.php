<?php
session_start();
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/auth.php";

// Require login
require_login();

$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['role'] === 'admin';

$student = null;
$error = null;
$search_roll = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_roll = trim($_POST["roll_no"]);

    if ($is_admin) {
        $stmt = $conn->prepare("SELECT * FROM students WHERE roll_no=?");
        $stmt->bind_param("s", $search_roll);
    } else {
        $stmt = $conn->prepare("SELECT * FROM students WHERE roll_no=? AND user_id=?");
        $stmt->bind_param("si", $search_roll, $user_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $student = $result->fetch_assoc();
    } else {
        $error = "No student found with Roll No: " . htmlspecialchars($search_roll);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Result Lookup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include __DIR__ . "/includes/navbar.php"; ?>

<div class="container mt-5">
    <h2 class="mb-4">Check Student Result</h2>

    <?php if ($is_admin): ?>
        <form method="post" class="mb-4 row g-2">
            <div class="col-md-6">
                <input type="text" name="roll_no" class="form-control" placeholder="Enter Roll No" required
                       value="<?= htmlspecialchars($search_roll) ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Search</button>
            </div>
        </form>
    <?php else: ?>
        <p>Viewing your own result.</p>
    <?php endif; ?>

    <?php if ($student): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Result Details</h4>
            </div>
            <div class="card-body">
                <p><strong>Roll No:</strong> <?= htmlspecialchars($student['roll_no']) ?></p>
                <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>

                <table class="table table-bordered mt-3">
                    <thead class="table-light">
                        <tr>
                            <th>Subject</th>
                            <th>Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Machine Learning</td><td><?= $student['ml'] ?></td></tr>
                        <tr><td>Software Engineering & Design</td><td><?= $student['sed'] ?></td></tr>
                        <tr><td>Design Thinking-II</td><td><?= $student['dt2'] ?></td></tr>
                        <tr><td>Web Technology</td><td><?= $student['wt'] ?></td></tr>
                        <tr><td>Elective-I</td><td><?= $student['elective1'] ?></td></tr>
                        <tr><td>Elective-II</td><td><?= $student['elective2'] ?></td></tr>
                    </tbody>
                </table>

                <a href="download_result.php?roll_no=<?= urlencode($student['roll_no']) ?>" class="btn btn-success">
                    <i class="bi bi-download"></i> Download PDF
                </a>
            </div>
        </div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
</div>

</body>
</html>
