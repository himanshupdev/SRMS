<?php
require_once "includes/auth.php";
require_student();

$student = $db->getStudentByRollNo($_SESSION['roll_no']);
if (!$student) {
    header("Location: logout.php");
    exit;
}

function calculateGrade($marks) {
    if ($marks >= 90) return ['A+', 'Excellent'];
    if ($marks >= 80) return ['A', 'Very Good'];
    if ($marks >= 70) return ['B', 'Good'];
    if ($marks >= 60) return ['C', 'Satisfactory'];
    if ($marks >= 50) return ['D', 'Pass'];
    return ['F', 'Fail'];
}

function calculateTotal($student) {
    return $student['ml'] + $student['sed'] + $student['dt2'] + 
           $student['wt'] + $student['elective1'] + $student['elective2'];
}

function calculatePercentage($total) {
    return round(($total / 600) * 100, 2);
}

$total = calculateTotal($student);
$percentage = calculatePercentage($total);
$overall_grade = calculateGrade($percentage)[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Result Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6B73FF;
            --secondary-color: #000DFF;
            --light-bg: #f8f9fa;
            --border-color: #e9ecef;
            --success-color: #4CAF50;
            --warning-color: #FFC107;
            --danger-color: #FF5252;
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
        
        .student-info {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .student-name {
            color: #2c3e50;
            font-weight: 600;
            margin: 0;
            font-size: 1.5rem;
        }
        
        .roll-number {
            color: #7f8c8d;
            margin: 0.5rem 0 0 0;
        }
        
        .result-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .result-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 1.5rem;
        }
        
        .result-title {
            margin: 0;
            font-weight: 600;
            font-size: 1.25rem;
        }
        
        .result-body {
            padding: 2rem;
        }
        
        .subject-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .subject-row:last-child {
            border-bottom: none;
        }
        
        .subject-name {
            color: #2c3e50;
            font-weight: 500;
        }
        
        .marks {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .marks-value {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .grade {
            padding: 0.25rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .grade-A\+ {
            background-color: #E3F2FD;
            color: #1976D2;
        }
        
        .grade-A {
            background-color: #E8F5E9;
            color: #43A047;
        }
        
        .grade-B {
            background-color: #FFF3E0;
            color: #FB8C00;
        }
        
        .grade-C {
            background-color: #FCE4EC;
            color: #EC407A;
        }
        
        .grade-D {
            background-color: #FFEBEE;
            color: #E53935;
        }
        
        .grade-F {
            background-color: #FFEBEE;
            color: #D32F2F;
        }
        
        .total-section {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0 0 15px 15px;
            border-top: 1px solid var(--border-color);
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .total-row:last-child {
            margin-bottom: 0;
        }
        
        .total-label {
            color: #2c3e50;
            font-weight: 500;
        }
        
        .total-value {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .btn-download {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-top: 2rem;
        }
        
        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(107, 115, 255, 0.3);
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Student Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
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
        <div class="student-info">
            <h1 class="student-name"><?= htmlspecialchars($student['name']) ?></h1>
            <p class="roll-number">Roll Number: <?= htmlspecialchars($student['roll_no']) ?></p>
        </div>

        <div class="result-card">
            <div class="result-header">
                <h2 class="result-title">Semester Results</h2>
            </div>
            
            <div class="result-body">
                <?php
                $subjects = [
                    'ml' => 'Machine Learning',
                    'sed' => 'Software Engineering & Design',
                    'dt2' => 'Digital Techniques II',
                    'wt' => 'Web Technology',
                    'elective1' => 'Elective I',
                    'elective2' => 'Elective II'
                ];
                
                foreach ($subjects as $key => $name):
                    $marks = $student[$key];
                    list($grade, $remark) = calculateGrade($marks);
                ?>
                <div class="subject-row">
                    <div class="subject-name"><?= htmlspecialchars($name) ?></div>
                    <div class="marks">
                        <span class="marks-value"><?= $marks ?>/100</span>
                        <span class="grade grade-<?= $grade ?>"><?= $grade ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="total-section">
                <div class="total-row">
                    <span class="total-label">Total Marks</span>
                    <span class="total-value"><?= $total ?>/600</span>
                </div>
                <div class="total-row">
                    <span class="total-label">Percentage</span>
                    <span class="total-value"><?= $percentage ?>%</span>
                </div>
                <div class="total-row">
                    <span class="total-label">Overall Grade</span>
                    <span class="grade grade-<?= $overall_grade ?>"><?= $overall_grade ?></span>
                </div>
            </div>
        </div>
        
        <a href="download_result.php" class="btn-download">
            <i class="bx bx-download"></i> Download Result
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
