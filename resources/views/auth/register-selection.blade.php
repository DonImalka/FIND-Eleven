<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Registration Type - Find11</title>
    <link rel="stylesheet" href="{{ asset('css/register-selection.css') }}">
</head>
<body>
    <div class="register-selection">
        <div class="selection-container">
            <div class="selection-header">
                <h1>Choose Your Registration Type</h1>
                <p>Select the role that best describes you to proceed with registration</p>
            </div>

            <div class="role-cards">
                <!-- School Registration -->
                <a href="{{ route('school.register') }}" class="role-card">
                    <div class="role-icon">🏫</div>
                    <h3>School</h3>
                    <p>Register your school to manage cricket programs and register student-athletes</p>
                    <span class="btn">Register as School</span>
                </a>

                <!-- Player Registration Note -->
                <div class="role-card" style="opacity: 0.7; cursor: not-allowed; background: #f8f9fa;">
                    <div class="role-icon">⚾</div>
                    <h3>Player</h3>
                    <p>Players are registered by their respective schools. Contact your school's cricket incharge.</p>
                    <span class="btn" style="background: #ccc; cursor: not-allowed;">Registered by Schools</span>
                </div>

                <!-- Admin Registration Note -->
                <div class="role-card" style="opacity: 0.7; cursor: not-allowed; background: #f8f9fa;">
                    <div class="role-icon">👨‍💼</div>
                    <h3>Admin</h3>
                    <p>Admin accounts are created by system administrators only. Contact support for assistance.</p>
                    <span class="btn" style="background: #ccc; cursor: not-allowed;">By Invitation Only</span>
                </div>
            </div>

            <div class="back-link">
                <a href="{{ route('home') }}">← Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
