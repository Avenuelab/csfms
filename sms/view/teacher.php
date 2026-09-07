<?php
if (!isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ../index.php');
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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['do']) && $_POST['do'] === 'add_teacher') {
    $index_number = $_POST['index_number'];
    $full_name = $_POST['full_name'];
    $i_name = $_POST['i_name'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO teacher (index_number, full_name, i_name, address, gender, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $index_number, $full_name, $i_name, $address, $gender, $phone, $email);

    if ($stmt->execute()) {
        echo '<meta http-equiv="refresh" content="2;URL=all_teacher.php">';
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<?php include_once('head.php'); ?>
<?php include_once('header_admin.php'); ?>
<?php include_once('sidebar.php'); ?>
<?php include_once('alert.php'); ?>

<style>

.msk-col-md-4{
	width:28%;
}
.modal{
	overflow-y: auto;
}

.form-control-feedback {
  
   pointer-events: auto;
  
}

.msk-set-width-tooltip + .tooltip > .tooltip-inner { 
  
     min-width:180px;
}
.msk-set-color-tooltip + .tooltip > .tooltip-inner { 
  
     min-width:180px;
	 background-color:red;
}
.msk-image-error{
	border:1px solid #f44336;
	
}

.msk-fade {  
      
    -webkit-animation-name: animatetop;
    -webkit-animation-duration: 0.4s;
    animation-name: animatetop;
    animation-duration: 0.4s

}

/* Add Animation */
@-webkit-keyframes animatetop {
    from {top:-300px; opacity:0} 
    to {top:0; opacity:1}
}

@keyframes animatetop {
    from {top:-300px; opacity:0}
    to {top:0; opacity:1}
}

@media only screen and (max-width: 500px) {
	
	#divGender1, #divPhone1, #divEmail1{
		
	 	width:75%;
		
	}

}

</style>


<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Teacher
            <small>Preview</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Teacher</a></li>
        </ol>
    </section>

    <section class="content">
        <div class="row" id="test123">
            <div class="col-md-7">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Teacher</h3>
                    </div>
                    <form role="form" action="" method="post" id="form1" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group" id="divIndexNumber">
                                <div class="col-xs-3"><label>Index Number</label></div>
                                <div class="col-xs-9"><input type="text" class="form-control" placeholder="Enter index number" name="index_number" id="index_number" required></div>
                            </div>
                            <div class="form-group" id="divFullName">
                                <div class="col-xs-3"><label>Full Name</label></div>
                                <div class="col-xs-9"><input type="text" class="form-control" placeholder="Enter full name" name="full_name" id="full_name" required></div>
                            </div>
                            <div class="form-group" id="divIName">
                                <div class="col-xs-3"><label>Name With Initials</label></div>
                                <div class="col-xs-9"><input type="text" class="form-control" placeholder="Enter name with initials" name="i_name" id="i_name" required></div>
                            </div>
                            <div class="form-group" id="divAddress">
                                <div class="col-xs-3"><label>Address</label></div>
                                <div class="col-xs-9"><input type="text" class="form-control" placeholder="Enter address" name="address" id="address" required></div>
                            </div>
                            <div class="form-group" id="divGender">
                                <div class="col-xs-3"><label>Gender</label></div>
                                <div class="col-xs-4" id="divGender1">
                                    <select name="gender" class="form-control" id="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="divPhone">
                                <div class="col-xs-3"><label>Phone Number</label></div>
                                <div class="col-xs-4" id="divPhone1">
                                    <input type="tel" class="form-control" placeholder="123-456-7890" name="phone" id="phone" required>
                                </div>
                            </div>
                            <div class="form-group" id="divEmail">
                                <div class="col-xs-3"><label>Email</label></div>
                                <div class="col-xs-6" id="divEmail1">
                                    <input type="email" class="form-control" placeholder="Enter valid email address" name="email" id="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="do" value="add_teacher" />
                            <button type="submit" class="btn btn-primary" id="btnSubmit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// JavaScript code for additional validation as needed.
</script>
<?php include_once('footer.php');?>