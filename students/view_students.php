<?php
require_once '../config/database.php';
require_once '../includes/session.php';

require_login();

// Fetch all student records in newest-first order.
$statement = $pdo->prepare(
    'SELECT id, registration_number, first_name, last_name, gender, school_level, created_at
     FROM students
     ORDER BY created_at DESC'
);
$statement->execute();
$students = $statement->fetchAll();

$page_title = 'Student Records';
$page_description = 'View all registered students in the system.';
$base_path = '..';
$active_page = 'view';

require_once '../includes/header.php';
?>

<main class="page-shell">
    <section class="content-panel">
        <div class="section-toolbar">
            <div>
                <span class="eyebrow">Records</span>
                <h2>Registered Students</h2>
            </div>
            <a class="button button-primary" href="add_student.php">Register Student</a>
        </div>

        <?php if (count($students) === 0): ?>
            <div class="empty-state">
                <h3>No student records found</h3>
                <p>Register the first student to start building the school records list.</p>
                <a class="button button-secondary" href="add_student.php">Add Student</a>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Registration Number</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Gender</th>
                            <th>School Level</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($student['registration_number']); ?></strong></td>
                                <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['gender']); ?></td>
                                <td><span class="status-pill"><?php echo htmlspecialchars($student['school_level']); ?></span></td>
                                <td><?php echo htmlspecialchars($student['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
