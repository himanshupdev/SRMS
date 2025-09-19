<?php
require_once __DIR__ . "/includes/xml_db.php";
session_start();

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $user = $db->authenticateUser($username, $password);
    
    if ($user) {
            // For students, verify that username matches a valid roll number
            if ($user['role'] === 'student') {
                $student = $db->getStudentByRollNo($user['username']);
                if (!$student) {
                    $msg = "<div class='alert alert-danger text-center'>❌ Student account not found in records. Please contact administrator.</div>";
                    // Don't proceed with login - show error message
                    goto show_form;
                }
            }
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                $_SESSION['roll_no'] = $user['username'];
                header("Location: dashboard_student.php");
            }
            exit;
        } else {
        $msg = "<div class='alert alert-danger text-center'>❌ Invalid username or password.</div>";
    }
}

show_form:
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Result Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            max-width: 400px;
            width: 90%;
            margin: 0 auto;
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h1 {
            color: #2c3e50;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: #7f8c8d;
            margin-bottom: 0;
        }
        .form-control {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            background: #fff;
            border-color: #6B73FF;
            box-shadow: 0 0 0 3px rgba(107, 115, 255, 0.1);
        }
        .btn-login {
            background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(107, 115, 255, 0.4);
        }
        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .signup-link a {
            color: #6B73FF;
            text-decoration: none;
            font-weight: 600;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Enter your credentials to access your account</p>
            </div>
            <?= $msg ?>
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-login text-white w-100">Login</button>
                <div class="signup-link">
                    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
