<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Find11</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="nav-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Find11 Logo" class="logo-img">
            </a>
            <ul class="nav-menu">
                <li><a href="{{ route('home') }}" class="nav-link">Home</a></li>
                <li><a href="{{ route('about') }}" class="nav-link">About</a></li>
                @guest
                    <li><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                    <li><a href="{{ route('register') }}" class="nav-link btn-primary">Register</a></li>
                @else
                    <li><a href="{{ route('dashboard') }}" class="nav-link btn-primary">Dashboard</a></li>
                @endguest
            </ul>
        </div>
    </nav>

    <!-- About Hero -->
    <section class="about-hero">
        <h1>About Find11</h1>
        <p>Discover the future of school cricket management</p>
    </section>

    <!-- About Content -->
    <div class="about-content">
        <div class="about-section">
            <h2>Our Mission</h2>
            <p>Find11 is dedicated to revolutionizing school cricket management in Sri Lanka. We provide a comprehensive platform that connects schools, coaches, and young cricket talent, making it easier to identify, nurture, and develop the next generation of cricket stars.</p>
            <p>Our mission is to create a unified ecosystem where every school cricket program can thrive, every talented player can be discovered, and every cricket enthusiast can contribute to the sport's growth.</p>
        </div>

        <div class="about-section">
            <h2>What We Do</h2>
            <p>Find11 serves as the central hub for school cricket management, offering:</p>
            <ul style="font-size: 1.1rem; line-height: 2; color: #666; margin-left: 2rem;">
                <li>School registration and verification system</li>
                <li>Comprehensive player profile management</li>
                <li>Age-based categorization (U13, U15, U17, U19)</li>
                <li>Player specialization tracking (batting, bowling, all-rounders)</li>
                <li>Performance monitoring and analytics</li>
                <li>Talent discovery for scouts and academies</li>
            </ul>
        </div>

        <div class="about-section">
            <h2>Our Vision</h2>
            <p>We envision a future where every talented young cricketer in Sri Lanka has the opportunity to be discovered and nurtured. Through Find11, we're building a transparent, efficient, and accessible platform that bridges the gap between school cricket programs and professional opportunities.</p>
        </div>

        <div class="about-section">
            <h2>Why Choose Us</h2>
            <div class="features-grid" style="margin-top: 2rem;">
                <div class="feature-card">
                    <h3 style="color: #667eea; margin-bottom: 0.5rem;">🔒 Secure</h3>
                    <p>Admin-verified school registrations ensure data authenticity</p>
                </div>
                <div class="feature-card">
                    <h3 style="color: #667eea; margin-bottom: 0.5rem;">📱 Easy to Use</h3>
                    <p>Intuitive interface designed for schools and coaches</p>
                </div>
                <div class="feature-card">
                    <h3 style="color: #667eea; margin-bottom: 0.5rem;">🚀 Innovative</h3>
                    <p>Modern technology meets traditional cricket development</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Find11</h3>
                <p>Empowering cricket schools to discover and nurture young talent across the nation.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Support</h3>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: info@find11.com</p>
                <p>Phone: +94 11 234 5678</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Find11. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
