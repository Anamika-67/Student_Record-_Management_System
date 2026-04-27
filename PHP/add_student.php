<?php
require_once 'config.php';
require_once 'auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize inputs
    $name = trim($_POST['name']);
    $roll_no = trim($_POST['roll_no']);
    $course = trim($_POST['course']);
    $marks = trim($_POST['marks']);
    
    // Basic PHP Validation
    if (empty($name) || empty($roll_no) || empty($course) || empty($marks)) {
        $error = "All fields are required.";
    } elseif (!is_numeric($marks) || $marks < 0 || $marks > 100) {
        $error = "Marks must be a number between 0 and 100.";
    } else {
        try {
            // Check for duplicate Roll No
            $checkStmt = $pdo->prepare("SELECT id FROM students WHERE roll_no = :roll_no");
            $checkStmt->execute([':roll_no' => $roll_no]);
            if ($checkStmt->rowCount() > 0) {
                $error = "A student with this Roll No already exists.";
            } else {
                // Prepared statement to prevent SQL Injection
                $stmt = $pdo->prepare("INSERT INTO students (name, roll_no, course, marks) VALUES (:name, :roll_no, :course, :marks)");
                
                // Bind parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':roll_no', $roll_no);
                $stmt->bindParam(':course', $course);
                $stmt->bindParam(':marks', $marks);
                
                if ($stmt->execute()) {
                    header("Location: index.php?msg=added");
                    exit();
                } else {
                    $error = "Failed to add student record.";
                }
            }
        } catch(PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- JS Validation -->
    <script>
        function validateForm() {
            let marks = document.getElementById('marks').value;
            if (marks < 0 || marks > 100) {
                alert('Marks must be between 0 and 100');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <header>
        <div class="brand">SRMS</div>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="logout.php" class="btn btn-outline btn-sm" style="margin-left: 1rem;">Logout</a>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <h2>Add New Student</h2>
            <a href="index.php" class="btn btn-outline">Back to Dashboard</a>
        </div>
        
        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="roll_no">Roll Number</label>
                    <input type="text" id="roll_no" name="roll_no" required value="<?php echo isset($_POST['roll_no']) ? htmlspecialchars($_POST['roll_no']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="course">Course / Department</label>
                    <select id="course" name="course" required>
                        <option value="">Select a Course</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Information Technology">Information Technology</option>
                        <option value="Software Engineering">Software Engineering</option>
                        <option value="Data Science">Data Science</option>
                        <option value="Business Administration">Business Administration</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="marks">Total Marks (%)</label>
                    <input type="number" step="0.01" id="marks" name="marks" required value="<?php echo isset($_POST['marks']) ? htmlspecialchars($_POST['marks']) : ''; ?>">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Save Record</button>
            </form>
        </div>
    </div>
</body>
</html>
