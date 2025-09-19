<?php
require_once "includes/auth.php";
require_admin();

$students = $db->getAllStudents();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Student Result Management</title>
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
        
        .dashboard-header {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .dashboard-title {
            color: #2c3e50;
            font-weight: 600;
            margin: 0;
        }
        
        .btn-add-student {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-add-student:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(107, 115, 255, 0.3);
        }
        
        .student-table {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            background: var(--light-bg);
            color: #2c3e50;
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
        }
        
        .table td {
            vertical-align: middle;
            color: #2c3e50;
        }
        
        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-edit {
            background-color: #ffeaa7;
            color: #fdcb6e;
        }
        
        .btn-delete {
            background-color: #ffe2e2;
            color: #ff7675;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #7f8c8d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
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
        <div class="dashboard-header d-flex justify-content-between align-items-center">
            <h1 class="dashboard-title">Manage Students</h1>
            <a href="add_student.php" class="btn btn-add-student text-white">
                <i class="bx bx-plus"></i> Add New Student
            </a>
        </div>
        
        <div class="student-table">
            <?php if (empty($students)): ?>
            <div class="empty-state">
                <i class="bx bx-user"></i>
                <h3>No Students Found</h3>
                <p>Start by adding a new student to the system.</p>
                <a href="add_student.php" class="btn btn-add-student text-white">
                    <i class="bx bx-plus"></i> Add Student
                </a>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Roll No</th>
                            <th>ML</th>
                            <th>SED</th>
                            <th>DT-II</th>
                            <th>Web Tech</th>
                            <th>Elective-I</th>
                            <th>Elective-II</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['id']) ?></td>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td><?= htmlspecialchars($student['roll_no']) ?></td>
                            <td><?= htmlspecialchars($student['ml']) ?></td>
                            <td><?= htmlspecialchars($student['sed']) ?></td>
                            <td><?= htmlspecialchars($student['dt2']) ?></td>
                            <td><?= htmlspecialchars($student['wt']) ?></td>
                            <td><?= htmlspecialchars($student['elective1']) ?></td>
                            <td><?= htmlspecialchars($student['elective2']) ?></td>
                            <td>
                                <a href="edit_student.php?id=<?= $student['id'] ?>" class="btn action-btn btn-edit me-2">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <a href="delete_student.php?id=<?= $student['id'] ?>" class="btn action-btn btn-delete" 
                                   onclick="return confirm('Are you sure you want to delete this student?')">
                                    <i class="bx bx-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
