<?php
require_once 'includes/session.php';

$page_title = 'Student Information Management System';
$page_description = '';
$base_path = '.';
$active_page = 'home';

require_once 'includes/header.php';
?>

<main class="page-shell">
    <section class="hero-section">
        <div class="hero-content">
            <span class="eyebrow">CP222 Open Source Assignment</span>
            <h1>Student Information Management System</h1>
            <p>
                Manage student records, user access, and registration details through a clean PHP and MySQL system.
            </p>
            <div class="hero-actions">
                <?php if (is_logged_in()): ?>
                    <a class="button button-primary" href="students/add_student.php">Register Student</a>
                    <a class="button button-secondary" href="students/view_students.php">View Records</a>
                <?php else: ?>
                    <a class="button button-primary" href="auth/login.php">Login to System</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="hero-panel" aria-label="System summary">
            <div>
                <strong>Primary</strong>
                <span>School level support</span>
            </div>
            <div>
                <strong>Secondary</strong>
                <span>Student records support</span>
            </div>
            <div>
                <strong>Secure</strong>
                <span>Session based access</span>
            </div>
        </div>
    </section>

    <section class="summary-grid">
        <article class="summary-card">
            <span class="card-number">01</span>
            <h2>User Access</h2>
            <p>Login, logout, and session management protect student pages from unauthorized access.</p>
        </article>

        <article class="summary-card">
            <span class="card-number">02</span>
            <h2>Student Records</h2>
            <p>Register students and view their registration number, names, gender, and school level.</p>
        </article>

        <article class="summary-card">
            <span class="card-number">03</span>
            <h2>Search</h2>
            <p>Find a student quickly using a unique registration number.</p>
        </article>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
