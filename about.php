<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - STOCKEASE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/Style.css">
    <link rel="stylesheet" href="css/about.css">
    <style>

        /* About Sections */
        .about-section {
            padding: 60px 20px;
            text-align: center;
        }
        .about-section h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .about-section p {
            font-size: 1.1rem;
            color: #555;
            max-width: 800px;
            margin: auto;
        }

        /* Mission and Vision */
        .mission-vision {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin-top: 30px;
        }
        .mission-vision div {
            max-width: 400px;
            text-align: center;
        }

        /* Developer Cards */
        .developer-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 40px;
            margin-top: 30px;
        }
        .developer-card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 250px;
            transition: transform 0.3s ease-in-out;
        }
        .developer-card:hover {
            transform: scale(1.05);
        }
        .developer-card img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 15px;
            object-fit: cover;
        }
        .developer-card h5 {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .developer-card p {
            color: #777;
            font-size: 1rem;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">STOCKEASE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="Home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link btn btn-primary text-black px-3" href="login.php">Log In</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>About STOCKEASE</h1>
        <p>Efficient, user-friendly inventory management for businesses of all sizes.</p>
    </div>
</section>

<!-- About Section -->
<div class="about-section">
    <h2>Who We Are</h2>
    <p>STOCKEASE is an advanced inventory management system designed to streamline stock tracking, sales processing, and customer management.</p>
</div>

<!-- Mission & Vision Section -->
<div class="container text-center">
    <div class="mission-vision">
        <div>
            <h3>Our Mission</h3>
            <p>We aim to provide a seamless inventory management experience that enhances business productivity and efficiency.</p>
        </div>
        <div>
            <h3>Our Vision</h3>
            <p>To become the leading inventory management solution, empowering businesses worldwide with innovative and user-friendly tools.</p>
        </div>
    </div>
</div>

<!-- Meet the Developers -->
<div class="about-section">
    <h2>Meet the Developers</h2>
    <div class="developer-container">
        <div class="developer-card">
            <img src="Pictures/yuv.jpg" alt="Yuvraj Pandey">
            <h5>Yuvraj Pandey</h5>
            <p>Lead Developer</p>
        </div>
        <div class="developer-card">
            <img src="Pictures/pwn.jpg" alt="Pawan Poudel">
            <h5>Pawan Poudel</h5>
            <p>UI/UX Designer</p>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="text-center mt-5 py-3 bg-light">
    <p>&copy; 2025 STOCKEASE. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
