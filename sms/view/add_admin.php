<?php
if (!isset($_SERVER['HTTP_REFERER'])) {
    header('location:../index.php');
    exit;
}

include_once('../controller/config.php');

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $index_number = $_POST['index_number'];
    $full_name = $_POST['full_name'];
    $i_name = $_POST['i_name'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Insert admin data into the database
    $reg_date = date('Y-m-d');
    $sql = "INSERT INTO admin (index_number, full_name, i_name, gender, address, phone, email, reg_date) 
            VALUES ('$index_number', '$full_name', '$i_name', '$gender', '$address', '$phone', '$email', '$reg_date')";

    if (mysqli_query($conn, $sql)) {
        echo '<meta http-equiv="refresh" content="2;URL=add_user.php">';
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
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
    <h2>Add Admin</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="index_number">Index Number:</label>
            <input type="number" class="form-control" id="index_number" name="index_number" required>
        </div>
        <div class="form-group">
            <label for="full_name">Full Name:</label>
            <input type="text" class="form-control" id="full_name" name="full_name" required>
        </div>
        <div class="form-group">
            <label for="i_name">Name with Initials:</label>
            <input type="text" class="form-control" id="i_name" name="i_name" required>
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Admin</button>
    </form>
</section>
</div>

<script src="path/to/bootstrap.bundle.min.js"></script> <!-- Adjust the path -->
<?php include_once('footer.php');?>
</body>
</html>