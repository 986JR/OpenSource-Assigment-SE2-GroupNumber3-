<?php
require_once '../config/database.php';
require_once '../includes/session.php';

require_login();

$errors = [];
$success = '';

$registration_number = '';
$first_name = '';
$last_name = '';
$gender = '';
$school_level = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registration_number = strtoupper(trim($_POST['registration_number'] ?? ''));
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $school_level = $_POST['school_level'] ?? '';

    if ($registration_number === '') {
        $errors[] = 'Registration number is required.';
    }

    if ($first_name === '') {
        $errors[] = 'First name is required.';
    }

    if ($last_name === '') {
        $errors[] = 'Last name is required.';
    }

    if (!in_array($gender, ['Male', 'Female'], true)) {
        $errors[] = 'Please select a valid gender.';
    }

    if (!in_array($school_level, ['Primary', 'Secondary'], true)) {
        $errors[] = 'Please select a valid school level.';
    }

    if (empty($errors)) {
        $check_statement = $pdo->prepare('SELECT id FROM students WHERE registration_number = ? LIMIT 1');
        $check_statement->execute([$registration_number]);

        if ($check_statement->fetch()) {
            $errors[] = 'A student with this registration number already exists.';
        }
    }

    if (empty($errors)) {
        // Prepared statements protect the student registration insert from SQL injection.
        $insert_statement = $pdo->prepare(
            'INSERT INTO students (registration_number, first_name, last_name, gender, school_level)
             VALUES (?, ?, ?, ?, ?)'
        );

        $insert_statement->execute([
            $registration_number,
            $first_name,
            $last_name,
            $gender,
            $school_level
        ]);

        $success = 'Student registered successfully.';
        $registration_number = '';
        $first_name = '';
        $last_name = '';
        $gender = '';
        $school_level = '';
    }
}

$page_title = 'Register Student';
$page_description = 'Add a new student record to the school database.';
$base_path = '..';
$active_page = 'add';

require_once '../includes/header.php';
?>

<main class="page-shell">
    <section class="content-panel">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="add_student.php" method="POST" class="student-form">
            <div class="form-group">
                <label for="registration_number">Registration Number</label>
                <input
                    type="text"
                    id="registration_number"
                    name="registration_number"
                    value="<?php echo htmlspecialchars($registration_number); ?>"
                    placeholder="Example: STD-001"
                    required
                >
            </div>

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    value="<?php echo htmlspecialchars($first_name); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    value="<?php echo htmlspecialchars($last_name); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="">Select gender</option>
                    <option value="Male" <?php echo $gender === 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo $gender === 'Female' ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="school_level">School Level</label>
                <select id="school_level" name="school_level" required>
                    <option value="">Select school level</option>
                    <option value="Primary" <?php echo $school_level === 'Primary' ? 'selected' : ''; ?>>Primary</option>
                    <option value="Secondary" <?php echo $school_level === 'Secondary' ? 'selected' : ''; ?>>Secondary</option>
                </select>
            </div>

            <div class="form-actions">
                <button class="button button-primary" type="submit">Register Student</button>
                <a class="button button-secondary" href="view_students.php">View Students</a>
            </div>
        </form>
    </section>
</main>

<?php require_once '../includes/footer.php'; ?>
