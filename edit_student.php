<?php
require_once "includes/auth.php";
require_admin();

$error = "";
$success = "";
$student = null;

if (isset($_GET['id'])) {
    $student = $db->getStudentById($_GET['id']);
    if (!$student) {
        header("Location: dashboard_admin.php");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = trim($_POST["name"]);
    $roll_no = trim($_POST["roll_no"]);
    $ml = trim($_POST["ml"]);
    $sed = trim($_POST["sed"]);
    $dt2 = trim($_POST["dt2"]);
    $wt = trim($_POST["wt"]);
    $elective1 = trim($_POST["elective1"]);
    $elective2 = trim($_POST["elective2"]);

    if (empty($name) || empty($roll_no)) {
        $error = "Name and Roll No are required fields.";
    } else {
        $result = $db->updateStudent($id, [
            'name' => $name,
            'roll_no' => $roll_no,
            'ml' => $ml,
            'sed' => $sed,
            'dt2' => $dt2,
            'wt' => $wt,
            'elective1' => $elective1,
            'elective2' => $elective2
        ]);

        if ($result) {
            $success = "Student updated successfully!";
            $student = $db->getStudentById($id);
        } else {
            $error = "Failed to update student. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Student Result Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6B73FF;
            --secondary-color: #000DFF;
            --light-bg: #f8f9fa;
            --border-color: #e9ecef;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            color: white !important;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }
        
        .form-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .form-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 2rem;
        }
        
        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(107, 115, 255, 0.25);
        }
        
        .form-label {
            color: #2c3e50;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(107, 115, 255, 0.3);
        }
        
        .btn-back {
            background: var(--light-bg);
            color: #2c3e50;
            border: 2px solid var(--border-color);
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard_admin.php">
                            <i class="bx bxs-dashboard"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_student.php">
                            <i class="bx bx-user-plus"></i> Add Student
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="bx bx-log-out"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-container">
                    <h2 class="form-title">Edit Student</h2>
                    
                    <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="bx bx-error-circle me-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="bx bx-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($student): ?>
                    <form method="POST" action="">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($student['id']) ?>">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Student Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($student['name']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="roll_no" class="form-label">Roll Number</label>
                                <input type="text" class="form-control" id="roll_no" name="roll_no" 
                                       value="<?= htmlspecialchars($student['roll_no']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="ml" class="form-label">Machine Learning</label>
                                <input type="number" class="form-control" id="ml" name="ml" 
                                       value="<?= htmlspecialchars($student['ml']) ?>" min="0" max="100">
                            </div>
                            <div class="col-md-6">
                                <label for="sed" class="form-label">Software Engineering & Design</label>
                                <input type="number" class="form-control" id="sed" name="sed" 
                                       value="<?= htmlspecialchars($student['sed']) ?>" min="0" max="100">
                            </div>
                            <div class="col-md-6">
                                <label for="dt2" class="form-label">Digital Techniques II</label>
                                <input type="number" class="form-control" id="dt2" name="dt2" 
                                       value="<?= htmlspecialchars($student['dt2']) ?>" min="0" max="100">
                            </div>
                            <div class="col-md-6">
                                <label for="wt" class="form-label">Web Technology</label>
                                <input type="number" class="form-control" id="wt" name="wt" 
                                       value="<?= htmlspecialchars($student['wt']) ?>" min="0" max="100">
                            </div>
                            <div class="col-md-6">
                                <label for="elective1" class="form-label">Elective I</label>
                                <input type="number" class="form-control" id="elective1" name="elective1" 
                                       value="<?= htmlspecialchars($student['elective1']) ?>" min="0" max="100">
                            </div>
                            <div class="col-md-6">
                                <label for="elective2" class="form-label">Elective II</label>
                                <input type="number" class="form-control" id="elective2" name="elective2" 
                                       value="<?= htmlspecialchars($student['elective2']) ?>" min="0" max="100">
                            </div>
                            <div class="col-12 mt-4">
                                <a href="dashboard_admin.php" class="btn btn-back me-2">
                                    <i class="bx bx-arrow-back"></i> Back
                                </a>
                                <button type="submit" class="btn btn-submit text-white">
                                    <i class="bx bx-save"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
