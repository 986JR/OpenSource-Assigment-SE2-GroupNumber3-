<?php
require_once '../config/database.php';
require_once '../includes/session.php';

require_login();

$registration_number = '';
$student = null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registration_number = strtoupper(trim($_POST['registration_number'] ?? ''));

    if ($registration_number === '') {
        $message = 'Please enter a registration number.';
    } else {
        // Prepared statements protect the search from SQL injection.
        $statement = $pdo->prepare(
            'SELECT id, registration_number, first_name, last_name, gender, school_level, created_at
             FROM students
             WHERE registration_number = ?
             LIMIT 1'
        );
        $statement->execute([$registration_number]);
        $student = $statement->fetch();

        if (!$student) {
            $message = 'No student found with that registration number.';
        }
    }
}

$page_title = 'Search Student';
$page_description = 'Find a student using their registration number.';
$base_path = '..';
$active_page = 'search';

require_once '../includes/header.php';
?>

<main class="page-shell">
    <section class="content-panel search-layout">
        <form action="search_student.php" method="POST" class="search-form">
            <div class="form-group">
                <label for="registration_number">Registration Number</label>
                <input
                    type="text"
                    id="registration_number"
                    name="registration_number"
                    value="<?php echo htmlspecialchars($registration_number); ?>"
                    placeholder="Enter registration number"
                    required
                >
            </div>

            <button class="button button-primary" type="submit">Search Student</button>
        </form>

        <?php if ($message !== ''): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($student): ?>
            <div class="result-card">
                <div class="result-heading">
                    <span class="eyebrow">Match Found</span>
                    <h2><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h2>
                    <p><?php echo htmlspecialchars($student['registration_number']); ?></p>
                </div>

                <dl class="details-grid">
                    <div>
                        <dt>Gender</dt>
                        <dd><?php echo htmlspecialchars($student['gender']); ?></dd>
                    </div>
                    <div>
                        <dt>School Level</dt>
                        <dd><?php echo htmlspecialchars($student['school_level']); ?></dd>
                    </div>
                    <div>
                        <dt>Created At</dt>
                        <dd><?php echo htmlspecialchars($student['created_at']); ?></dd>
                    </div>
                </dl>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
