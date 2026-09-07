<?php
include_once('../controller/config.php');

// Initialize variables
$search_index = '';
$students = [];

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_index = $_POST['index_number'];

    // Prepare the SQL statement to search for students by index_number
    $sql = "SELECT * FROM student WHERE index_number = '$search_index'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Fetch all matching students
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<?php include_once('head.php'); ?>
<?php include_once('header_admin.php'); ?>
<?php include_once('sidebar.php'); ?>
<?php include_once('alert.php'); ?>


<body>

    <div class="content-wrapper">
        <section class="content">
    <h2>Search for Students</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="index_number">Index Number:</label>
            <input type="number" class="form-control" id="index_number" name="index_number" value="<?php echo htmlspecialchars($search_index); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <?php if (!empty($students)): ?>
        <h3>Search Results:</h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Index Number</th>
                    <th>Full Name</th>
                    <th>Name with Initials</th>
                    <th>Gender</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Date of Birth</th>
                    <th>Status</th>
                    <th>Registration Year</th>
                    <th>Registration Month</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo $student['id']; ?></td>
                        <td><?php echo $student['index_number']; ?></td>
                        <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($student['i_name']); ?></td>
                        <td><?php echo htmlspecialchars($student['gender']); ?></td>
                        <td><?php echo htmlspecialchars($student['address']); ?></td>
                        <td><?php echo htmlspecialchars($student['phone']); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td><?php echo htmlspecialchars($student['b_date']); ?></td>
                        <td><?php echo htmlspecialchars($student['_status']); ?></td>
                        <td><?php echo $student['reg_year']; ?></td>
                        <td><?php echo htmlspecialchars($student['reg_month']); ?></td>
                        <td><?php echo htmlspecialchars($student['reg_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <p>No students found with that index number.</p>
    <?php endif; ?>
</div>

<script src="path/to/bootstrap.bundle.min.js"></script> <!-- Adjust the path -->
<?php include_once('footer.php');?>
</body>
</html>