<?php
require_once 'config.php';
require_once 'auth.php'; // Ensures the user is logged in

// Fetch all students from the database
try {
    $stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
    $students = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error fetching data: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="brand">SRMS</div>
        <div class="nav-links">
            <span style="color: var(--text-main); font-weight: 600;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php" class="btn btn-outline btn-sm" style="margin-left: 1rem;">Logout</a>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <h2>Student Records</h2>
            <a href="add_student.php" class="btn btn-primary">+ Add New Student</a>
        </div>
        
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'added'): ?>
            <div class="alert alert-success">Student added successfully!</div>
        <?php endif; ?>
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">Student record updated successfully!</div>
        <?php endif; ?>
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">Student deleted successfully!</div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Roll No</th>
                        <th>Course</th>
                        <th>Marks</th>
                        <th>Added On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($students) > 0): ?>
                        <?php foreach($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['id']); ?></td>
                                <td style="font-weight: 500;"><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo htmlspecialchars($student['roll_no']); ?></td>
                                <td><span style="background: #e0e7ff; color: #4338ca; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.875rem; font-weight: 500;"><?php echo htmlspecialchars($student['course']); ?></span></td>
                                <td style="font-weight: 600; color: <?php echo ($student['marks'] < 50) ? 'var(--danger-color)' : 'var(--secondary-color)'; ?>;">
                                    <?php echo htmlspecialchars($student['marks']); ?>%
                                </td>
                                <td><?php echo date('M d, Y', strtotime($student['created_at'])); ?></td>
                                <td class="actions">
                                    <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-outline btn-sm">Edit</a>
                                    <a href="delete_student.php?id=<?php echo $student['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                                No student records found. Add a new student to get started.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
