<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    // Database connection
    $host = 'localhost'; // Database host
    $db = 'DemoSMS'; // Database name
    $user = 'root'; // Database username
    $pass = ''; // Database password

    $conn = new mysqli($host, $user, $pass, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password']; // Use the password directly without hashing
    $type = $_POST['type'];

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO `user` (`email`, `password`, `type`) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $password, $type);

    // Execute and check for success
    if ($stmt->execute()) {
        $message = "User added successfully.";
    } else {
        $message = "Error adding user: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<?php include_once('head.php'); ?>
<?php include_once('header_admin.php'); ?>
<?php include_once('sidebar.php'); ?>
<?php include_once('alert.php'); ?>


<body>

    <div class="content-wrapper">
        <section class="content">
        <h2>Add User</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="type">User Type:</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="Admin">Admin</option>
                    <option value="Teacher">Teacher</option>
                    <option value="Parents">Parents</option>
                    <option value="Student">Student</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="add_user">Add User</button>
        </form>
    </section>
    </div>

    <script src="path/to/bootstrap.bundle.min.js"></script> <!-- Include your JavaScript -->
    <?php include_once('footer.php');?>
</body>
</html>