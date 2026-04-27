<?php
require_once 'config.php';
require_once 'auth.php';

$error = '';
$student = null;

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Fetch existing data
try {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $student = $stmt->fetch();
    
    if (!$student) {
        die("Student not found!");
    }
} catch(PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// Handle Form Submission for Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $roll_no = trim($_POST['roll_no']);
    $course = trim($_POST['course']);
    $marks = trim($_POST['marks']);
    
    if (empty($name) || empty($roll_no) || empty($course) || empty($marks)) {
        $error = "All fields are required.";
    } elseif (!is_numeric($marks) || $marks < 0 || $marks > 100) {
        $error = "Marks must be a number between 0 and 100.";
    } else {
        try {
            // Check for duplicate Roll No (excluding current student)
            $checkStmt = $pdo->prepare("SELECT id FROM students WHERE roll_no = :roll_no AND id != :id");
            $checkStmt->execute([':roll_no' => $roll_no, ':id' => $id]);
            
            if ($checkStmt->rowCount() > 0) {
                $error = "Another student with this Roll No already exists.";
            } else {
                $updateStmt = $pdo->prepare("UPDATE students SET name = :name, roll_no = :roll_no, course = :course, marks = :marks WHERE id = :id");
                
                $updateStmt->bindParam(':name', $name);
                $updateStmt->bindParam(':roll_no', $roll_no);
                $updateStmt->bindParam(':course', $course);
                $updateStmt->bindParam(':marks', $marks);
                $updateStmt->bindParam(':id', $id);
                
                if ($updateStmt->execute()) {
                    header("Location: index.php?msg=updated");
                    exit();
                } else {
                    $error = "Failed to update student record.";
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
    <title>Edit Student - System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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
            <h2>Edit Student Record</h2>
            <a href="index.php" class="btn btn-outline">Back to Dashboard</a>
        </div>
        
        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($student['name']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="roll_no">Roll Number</label>
                    <input type="text" id="roll_no" name="roll_no" required value="<?php echo htmlspecialchars($student['roll_no']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="course">Course / Department</label>
                    <select id="course" name="course" required>
                        <option value="Computer Science" <?php if($student['course'] == 'Computer Science') echo 'selected'; ?>>Computer Science</option>
                        <option value="Information Technology" <?php if($student['course'] == 'Information Technology') echo 'selected'; ?>>Information Technology</option>
                        <option value="Software Engineering" <?php if($student['course'] == 'Software Engineering') echo 'selected'; ?>>Software Engineering</option>
                        <option value="Data Science" <?php if($student['course'] == 'Data Science') echo 'selected'; ?>>Data Science</option>
                        <option value="Business Administration" <?php if($student['course'] == 'Business Administration') echo 'selected'; ?>>Business Administration</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="marks">Total Marks (%)</label>
                    <input type="number" step="0.01" id="marks" name="marks" required value="<?php echo htmlspecialchars($student['marks']); ?>">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Update Record</button>
            </form>
        </div>
    </div>
</body>
</html>
