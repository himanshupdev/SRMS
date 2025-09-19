<?php
require_once "includes/auth.php";
require_once "vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// Ensure user is logged in and has student role
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

$subjects = [
    'ml' => 'Machine Learning',
    'sed' => 'Software Engineering & Design',
    'dt2' => 'Digital Techniques II',
    'wt' => 'Web Technology',
    'elective1' => 'Elective I',
    'elective2' => 'Elective II'
];

// Create PDF content
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Academic Result</title>
    <style>
        body {
            font-family: "Helvetica", sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #6B73FF;
            padding-bottom: 20px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #000DFF;
            margin: 0;
        }
        .document-title {
            font-size: 18px;
            color: #666;
            margin: 10px 0;
        }
        .student-info {
            margin-bottom: 30px;
        }
        .student-info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #6B73FF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .grade {
            font-weight: bold;
        }
        .grade-A\+ { color: #1976D2; }
        .grade-A { color: #43A047; }
        .grade-B { color: #FB8C00; }
        .grade-C { color: #EC407A; }
        .grade-D { color: #E53935; }
        .grade-F { color: #D32F2F; }
        .summary {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .summary p {
            margin: 10px 0;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .signature-line {
            margin-top: 100px;
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="school-name">Student Result Management System</h1>
        <p class="document-title">Academic Result Certificate</p>
    </div>

    <div class="student-info">
        <p><strong>Student Name:</strong> ' . htmlspecialchars($student['name']) . '</p>
        <p><strong>Roll Number:</strong> ' . htmlspecialchars($student['roll_no']) . '</p>
        <p><strong>Date Generated:</strong> ' . date('d/m/Y') . '</p>
    </div>

    <table>
        <tr>
            <th>Subject</th>
            <th>Marks</th>
            <th>Grade</th>
            <th>Remarks</th>
        </tr>';

foreach ($subjects as $key => $name) {
    $marks = $student[$key];
    list($grade, $remark) = calculateGrade($marks);
    $html .= '
        <tr>
            <td>' . htmlspecialchars($name) . '</td>
            <td>' . $marks . '/100</td>
            <td class="grade grade-' . $grade . '">' . $grade . '</td>
            <td>' . $remark . '</td>
        </tr>';
}

$html .= '
    </table>

    <div class="summary">
        <p><strong>Total Marks:</strong> ' . $total . '/600</p>
        <p><strong>Percentage:</strong> ' . $percentage . '%</p>
        <p><strong>Overall Grade:</strong> <span class="grade grade-' . $overall_grade . '">' . $overall_grade . '</span></p>
    </div>

    <div class="footer">
        <div class="signature-line">
            Principal\'s Signature
        </div>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>Generated on ' . date('d/m/Y H:i:s') . '</p>
    </div>
</body>
</html>';

// Configure Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

// Create new PDF document
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Generate file name
$filename = 'Result_' . $student['roll_no'] . '_' . date('Y-m-d') . '.pdf';

// Download the PDF
$dompdf->stream($filename, array('Attachment' => true));
exit;
