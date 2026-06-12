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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Student - Student Information Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <h1>Search Student</h1>
            <p>Find a student using their registration number.</p>
        </div>
    </header>

    <main class="container">
        <section class="form-section">
            <div class="page-actions">
                <a href="add_student.php">Register New Student</a>
                <a href="view_students.php">View Students</a>
                <a href="../auth/logout.php">Logout</a>
            </div>

            <form action="search_student.php" method="POST">
                <div class="form-group">
                    <label for="registration_number">Registration Number</label>
                    <input
                        type="text"
                        id="registration_number"
                        name="registration_number"
                        value="<?php echo htmlspecialchars($registration_number); ?>"
                        required
                    >
                </div>

                <button type="submit">Search</button>
            </form>

            <?php if ($message !== ''): ?>
                <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <?php if ($student): ?>
                <div class="student-result">
                    <h2>Student Details</h2>
                    <table>
                        <tbody>
                            <tr>
                                <th>Registration Number</th>
                                <td><?php echo htmlspecialchars($student['registration_number']); ?></td>
                            </tr>
                            <tr>
                                <th>First Name</th>
                                <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td><?php echo htmlspecialchars($student['gender']); ?></td>
                            </tr>
                            <tr>
                                <th>School Level</th>
                                <td><?php echo htmlspecialchars($student['school_level']); ?></td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td><?php echo htmlspecialchars($student['created_at']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
