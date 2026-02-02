<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find11 - Cricket School Management Platform</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
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

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Find11</h1>
            <p>The Ultimate Cricket School Management Platform - Empowering schools to discover and nurture the next generation of cricket stars</p>
            <div class="hero-buttons">
                <a href="{{ route('register') }}" class="btn btn-white">Get Started</a>
                <a href="{{ route('about') }}" class="btn btn-outline">Learn More</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Why Choose Find11?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🏫</div>
                    <h3>School Management</h3>
                    <p>Complete platform for schools to register, manage cricket programs, and track student-athletes efficiently.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Player Registration</h3>
                    <p>Easy player registration system with detailed profiles including age categories, playing styles, and specializations.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Performance Tracking</h3>
                    <p>Track player development, skills, and progress throughout their cricket journey.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3>Age Categories</h3>
                    <p>Automatic categorization into U13, U15, U17, and U19 groups based on player age.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏆</div>
                    <h3>Talent Discovery</h3>
                    <p>Help scouts and academies discover talented young cricketers across different schools.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">✅</div>
                    <h3>Admin Approval</h3>
                    <p>Secure registration process with admin verification to ensure data authenticity.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">{{ $totalSchools }}+</div>
                    <div class="stat-label">Registered Schools</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $totalPlayers }}+</div>
                    <div class="stat-label">Young Cricketers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">4</div>
                    <div class="stat-label">Age Categories</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Ready to Join Find11?</h2>
            <p>Register your school today and start managing your cricket program efficiently. Help us discover the next cricket superstar!</p>
            <a href="{{ route('register') }}" class="btn btn-white" style="background: #667eea; color: white;">Register Your School Now</a>
        </div>
    </section>

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
