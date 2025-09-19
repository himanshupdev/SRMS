<?php
session_start();

// Redirect based on logged-in role
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: dashboard_admin.php");
        exit;
    } elseif ($_SESSION['role'] === 'student') {
        header("Location: dashboard_student.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --accent: #7209b7;
            --light: #f8f9fa;
            --dark: #212529;
            --gradient-start: #4361ee;
            --gradient-end: #3a0ca3;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333;
        }

        /* Custom Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff !important;
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0 0.5rem;
        }

        .nav-link:hover {
            color: #fff !important;
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            height: 85vh;
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.9), rgba(58, 12, 163, 0.9)), 
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1470&q=80') no-repeat center center/cover;
            display: flex;
            align-items: center;
            position: relative;
            color: #fff;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            width: 100%;
            padding: 0 20px;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: 1.4rem;
            margin-bottom: 2.5rem;
            font-weight: 300;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-hero {
            min-width: 160px;
            margin: 0 10px;
            border-radius: 50px;
            font-size: 1.2rem;
            padding: 15px 30px;
            transition: all 0.3s ease;
            font-weight: 600;
            border: 2px solid transparent;
        }

        .btn-hero-primary {
            background: #fff;
            color: var(--primary);
            border-color: #fff;
        }

        .btn-hero-primary:hover {
            background: transparent;
            color: #fff;
            border-color: #fff;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 30px rgba(255,255,255,0.3);
        }

        .btn-hero-outline {
            background: transparent;
            color: #fff;
            border-color: #fff;
        }

        .btn-hero-outline:hover {
            background: #fff;
            color: var(--primary);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 30px rgba(255,255,255,0.3);
        }

        /* Features */
        .features {
            padding: 80px 15px;
            background: var(--light);
        }

        .feature-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            background: #fff;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .feature-card i {
            font-size: 3.5rem;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
            display: inline-block;
        }

        .feature-card h4 {
            color: var(--dark);
            font-weight: 600;
            margin-bottom: 15px;
        }

        .feature-card p {
            color: #666;
            font-size: 1rem;
        }

        /* Info Section */
        .info-section {
            padding: 80px 15px;
            background: #fff;
        }

        .info-section h3 {
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 30px;
        }

        .info-section ul {
            list-style: none;
            padding: 0;
        }

        .info-section ul li {
            padding: 10px 0;
            font-size: 1.1rem;
            color: #555;
        }

        .info-section ul li:before {
            content: "✓";
            color: var(--primary);
            font-weight: bold;
            margin-right: 10px;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: #fff;
            padding: 40px 0 20px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-links {
            display: flex;
            gap: 30px;
        }

        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #fff;
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-links a {
            color: #fff;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .social-links a:hover {
            transform: translateY(-3px);
        }

        .copyright {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.7);
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            .hero p { font-size: 1.1rem; }
            .btn-hero { 
                min-width: 140px; 
                margin: 10px 5px;
                font-size: 1rem;
                padding: 12px 25px;
            }
            .footer-content {
                flex-direction: column;
                text-align: center;
            }
            .footer-links {
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .hero h1 { font-size: 2rem; }
            .hero { height: 75vh; }
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            🎓 Student Result Management
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard_admin.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="manage_students.php">Manage Students</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard_student.php">My Results</a></li>
                        <li class="nav-item"><a class="nav-link" href="result_lookup.php">View Results</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="signup.php">Signup</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero d-flex justify-content-center align-items-center">
    <div class="hero-content">
        <h1>Transform Student Result Management</h1>
        <p>Streamline academic records, enhance efficiency, and deliver seamless result management for institutions</p>
        <div class="hero-buttons">
            <a href="login.php" class="btn btn-hero btn-hero-primary">
                <i class="bi bi-box-arrow-in-right"></i> Get Started
            </a>
            <a href="#features" class="btn btn-hero btn-hero-outline">
                <i class="bi bi-play-circle"></i> Learn More
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="features container text-center">
    <div class="mb-5">
        <h2 class="display-5 fw-bold mb-3" style="color: var(--primary);">Powerful Features</h2>
        <p class="lead text-muted">Everything you need to manage student results securely and efficiently</p>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="feature-card">
                <i class="bi bi-shield-lock"></i>
                <h4>Advanced Security</h4>
                <p>Role-based authentication with secure session management and encrypted data protection</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <i class="bi bi-database"></i>
                <h4>Smart Data Management</h4>
                <p>Efficient CRUD operations with XML database for seamless student record management</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <i class="bi bi-file-earmark-pdf"></i>
                <h4>PDF Generation</h4>
                <p>Automated PDF result generation with professional formatting and instant downloads</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <i class="bi bi-graph-up"></i>
                <h4>Real-time Analytics</h4>
                <p>Comprehensive dashboard with performance metrics and academic progress tracking</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <i class="bi bi-phone"></i>
                <h4>Mobile Responsive</h4>
                <p>Fully responsive design that works perfectly on all devices from desktop to mobile</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card">
                <i class="bi bi-lightning"></i>
                <h4>High Performance</h4>
                <p>Optimized for speed with fast loading times and smooth user experience</p>
            </div>
        </div>
    </div>
</section>

<!-- Info Section -->
<section id="about" class="info-section container">
    <div class="row align-items-center">
        <div class="col-lg-6 mb-5 mb-lg-0">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55a?auto=format&fit=crop&w=800&q=80" 
                 class="img-fluid rounded-3 shadow" 
                 alt="Modern Education Technology"
                 style="transform: perspective(1000px) rotateY(-5deg); transition: transform 0.3s ease;">
        </div>
        <div class="col-lg-6">
            <h3 class="display-6 fw-bold mb-4">Why Choose Our Platform?</h3>
            <p class="lead mb-4">Designed for educational institutions seeking excellence in academic management. Our platform combines cutting-edge technology with user-friendly design.</p>
            <ul class="list-unstyled">
                <li class="mb-3">✅ <strong>Role-based Access Control</strong> - Separate dashboards for admins and students</li>
                <li class="mb-3">✅ <strong>Comprehensive Student Management</strong> - Full CRUD operations with XML database</li>
                <li class="mb-3">✅ <strong>Professional Result Reports</strong> - PDF generation with institutional branding</li>
                <li class="mb-3">✅ <strong>Modern UI/UX Design</strong> - Intuitive interface with responsive layout</li>
                <li class="mb-3">✅ <strong>Secure Authentication</strong> - Protected login system with session management</li>
                <li class="mb-3">✅ <strong>Scalable Architecture</strong> - Ready for institutional growth and expansion</li>
            </ul>
            <div class="mt-4">
                <a href="signup.php" class="btn btn-primary btn-lg px-5 py-3 rounded-pill">
                    <i class="bi bi-rocket-takeoff"></i> Start Your Journey
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5" style="background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-md-3 col-6 mb-4">
                <h2 class="display-4 fw-bold">1000+</h2>
                <p class="mb-0">Students Managed</p>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <h2 class="display-4 fw-bold">99.9%</h2>
                <p class="mb-0">Uptime Reliability</p>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <h2 class="display-4 fw-bold">24/7</h2>
                <p class="mb-0">Support Available</p>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <h2 class="display-4 fw-bold">50+</h2>
                <p class="mb-0">Institutions Trust Us</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <h4 class="mb-3">🎓 Student Result Management</h4>
                <p class="mb-0" style="color: rgba(255,255,255,0.8);">
                    Transforming education through innovative technology solutions
                </p>
            </div>
            
            <div class="footer-links">
                <a href="index.php">Home</a>
                <a href="#features">Features</a>
                <a href="#about">About</a>
                <a href="login.php">Login</a>
                <a href="signup.php">Sign Up</a>
            </div>
            
            <div class="social-links">
                <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" title="Twitter"><i class="bi bi-twitter"></i></a>
                <a href="#" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" title="GitHub"><i class="bi bi-github"></i></a>
            </div>
        </div>
        
        <div class="copyright">
            <p class="mb-0">&copy; <?php echo date("Y"); ?> Student Result Management System. All rights reserved.</p>
            <p class="mb-0">Built with ❤️ for educational institutions worldwide</p>
        </div>
    </div>
</footer>

</body>
</html>
