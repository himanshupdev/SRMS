<?php
require_once __DIR__ . "/includes/xml_db.php";
session_start();

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        $msg = "<div class='alert alert-danger text-center'>❌ Passwords do not match.</div>";
    } else {
        $result = $db->createUser($username, $password, 'student');
        
        if ($result) {
            $msg = "<div class='alert alert-success text-center'>✅ Account created successfully! <a href='login.php'>Login here</a></div>";
        } else {
            $msg = "<div class='alert alert-danger text-center'>❌ Username already exists.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Student Result Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .signup-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            max-width: 400px;
            width: 90%;
            margin: 0 auto;
        }
        .signup-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .signup-header h1 {
            color: #2c3e50;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .signup-header p {
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
        .btn-signup {
            background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(107, 115, 255, 0.4);
        }
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .login-link a {
            color: #6B73FF;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="signup-container">
            <div class="signup-header">
                <h1>Create Account</h1>
                <p>Sign up to access your student portal</p>
            </div>
            <?= $msg ?>
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="btn btn-signup text-white w-100">Sign Up</button>
                <div class="login-link">
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
