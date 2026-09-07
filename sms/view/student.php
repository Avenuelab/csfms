<?php
if (!isset($_SERVER['HTTP_REFERER'])) {
    header('location:../index.php');
    exit;
}

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['do']) && $_POST['do'] === 'add_student') {
    $index_number = $_POST['index_number'];
    $full_name = $_POST['full_name'];
    $i_name = $_POST['i_name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $b_date = $_POST['b_date'];
    $gender = $_POST['gender'];

    $g_index = $_POST['g_index'];
    $g_full_name = $_POST['g_full_name'];
    $g_i_name = $_POST['g_i_name'];
    $g_address = $_POST['g_address'];
    $g_email = $_POST['g_email'];
    $g_phone = $_POST['g_phone'];
    $g_b_date = $_POST['g_b_date'];
    $g_gender = $_POST['g_gender'];

    // Insert student details
    $stmt = $conn->prepare("INSERT INTO student (index_number, full_name, i_name, address, email, phone, b_date, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $index_number, $full_name, $i_name, $address, $email, $phone, $b_date, $gender);
    
    if ($stmt->execute()) {
        echo '<meta http-equiv="refresh" content="2;URL=all_student.php">';
    } else {
        echo "Error: " . $stmt->error;
    }

    // Insert guardian details
    $stmt = $conn->prepare("INSERT INTO parents (index_number, full_name, i_name, address, email, phone, b_date, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $g_index, $g_full_name, $g_i_name, $g_address, $g_email, $g_phone, $g_b_date, $g_gender);
    
    if ($stmt->execute()) {
        echo '<meta http-equiv="refresh" content="2;URL=all_student.php">';
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<?php include_once('head.php'); ?>
<?php include_once('header_admin.php'); ?>
<?php include_once('sidebar.php'); ?>
<?php include_once('alert.php'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Student
            <small>Preview</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Student</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Student</h3>
                    </div><!-- /.box-header -->
                    <form role="form" action="" method="post" id="form1" class="form-horizontal">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="alert-info">Student Details</p>
                                    <div class="form-group" id="divIndexNumber">
                                        <div class="col-xs-3">
                                            <label for="index_number">Index Number</label>
                                        </div>
                                        <div class="col-xs-9" id="divIndexNumber1">
                                            <input type="text" class="form-control" placeholder="Enter index number" name="index_number" id="index_number" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="form-group" id="divFullName">
                                        <div class="col-xs-3">
                                            <label for="full_name">Full Name</label>
                                        </div>
                                        <div class="col-xs-9" id="divFullName1">
                                            <input type="text" class="form-control" placeholder="Enter full name" name="full_name" id="full_name" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="form-group" id="divIName">
                                        <div class="col-xs-3">
                                            <label for="i_name">Name With Initials</label>
                                        </div>
                                        <div class="col-xs-9" id="divIName1">
                                            <input type="text" class="form-control" placeholder="Enter name with initials" name="i_name" id="i_name" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="form-group" id="divAddress">
                                        <div class="col-xs-3">
                                            <label for="address">Address</label>
                                        </div>
                                        <div class="col-xs-9" id="divAddress1">
                                            <input type="text" class="form-control" placeholder="Enter address" name="address" id="address" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="form-group" id="divEmail">
                                        <div class="col-xs-3">
                                            <label for="email">Email</label>
                                        </div>
                                        <div class="col-xs-9" id="divEmail1">
                                            <input type="email" class="form-control" placeholder="Enter email address" name="email" id="email" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-7">
                                            <div class="form-group" id="divPhone">
                                                <div class="col-xs-5">
                                                    <label for="phone">Phone</label>
                                                </div>
                                                <div class="col-xs-7" id="divPhone1">
                                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="111-111-1111" required>
                                                </div>
                                            </div>
                                            <div class="form-group" id="divDOB">
                                                <div class="col-xs-5">
                                                    <label for="b_date">Date of Birth</label>
                                                </div>
                                                <div class="col-xs-7" id="divDOB1">
                                                    <input type="date" class="form-control" id="b_date" name="b_date" required>
                                                </div>
                                            </div>
                                            <div class="form-group" id="divGender">
                                                <div class="col-xs-5">
                                                    <label for="gender">Gender</label>
                                                </div>
                                                <div class="col-xs-7" id="divGender1">
                                                    <select name="gender" class="form-control" id="gender" required>
                                                        <option value="">Select Gender</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="alert-info">Guardian Details</p>
                                    <div class="form-group" id="divGIndexNumber">
                                        <div class="col-xs-3">
                                            <label for="g_index">Index Number</label>
                                        </div>
                                        <div class="col-xs-9" id="divGIndexNumber1">
                                            <input type="text" class="form-control" placeholder="Enter index number" name="g_index" id="g_index" autocomplete="off" required readonly>
                                        </div>
                                    </div>
                                    <div class="form-group" id="divGFullName">
                                        <div class="col-xs-3">
                                            <label for="g_full_name">Full Name</label>
                                        </div>
                                        <div class="col-xs-9" id="divGFullName1">
                                            <input type="text" class="form-control" placeholder="Enter full name" name="g_full_name" id="g_full_name" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="form-group" id="divGIName">
                                        <div class="col-xs-3">
                                            <label for="g_i_name">Name With Initials</label>
                                        </div>
                                        <div class="col-xs-9" id="divGIName1">
                                            <input type="text" class="form-control" placeholder="Enter name with initials" name="g_i_name" id="g_i_name" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="form-group" id="divGAddress">
                                        <div class="col-xs-3">
                                            <label for="g_address">Address</label>
                                        </div>
                                        <div class="col-xs-9" id="divGAddress1">
                                            <input type="text" class="form-control" placeholder="Enter address" name="g_address" id="g_address" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="form-group" id="divGEmail">
                                        <div class="col-xs-3">
                                            <label for="g_email">Email</label>
                                        </div>
                                        <div class="col-xs-9" id="divGEmail1">
                                            <input type="email" class="form-control" placeholder="Enter email address" name="g_email" id="g_email" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-7">
                                            <div class="form-group" id="divGPhone">
                                                <div class="col-xs-5">
                                                    <label for="g_phone">Phone</label>
                                                </div>
                                                <div class="col-xs-7" id="divGPhone1">
                                                    <input type="text" class="form-control" id="g_phone" name="g_phone" placeholder="111-111-1111" required>
                                                </div>
                                            </div>
                                            <div class="form-group" id="divGDOB">
                                                <div class="col-xs-5">
                                                    <label for="g_b_date">Date of Birth</label>
                                                </div>
                                                <div class="col-xs-7" id="divGDOB1">
                                                    <input type="date" class="form-control" id="g_b_date" name="g_b_date" required>
                                                </div>
                                            </div>
                                            <div class="form-group" id="divGGender">
                                                <div class="col-xs-5">
                                                    <label for="g_gender">Gender</label>
                                                </div>
                                                <div class="col-xs-7" id="divGGender1">
                                                    <select name="g_gender" class="form-control" id="g_gender" required>
                                                        <option value="">Select Gender</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><br>
                        </div>
                        <div class="box-footer text-right">
                            <input type="hidden" name="do" value="add_student" />
                            <button style="width:150px;" type="submit" class="btn text-right btn-success" id="btnSubmit">Next</button><br>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- End of form section -->
</div>
   
<!-- Form validate (Before submit the form) -->     

                            
<?php include_once('footer.php');?>