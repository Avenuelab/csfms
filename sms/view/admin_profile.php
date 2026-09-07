<?php
if(!isset($_SERVER['HTTP_REFERER'])){
    header('location:../index.php');
    exit;
}
?>
<?php include_once('head.php'); ?>
<?php include_once('header_admin.php'); ?>
<?php include_once('sidebar.php'); ?>
<?php include_once('alert.php'); ?>

<style>
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
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            My Profile
            <small>Preview</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">My Profile</a></li>
        </ol>
    </section>

    <?php 
    include_once('../controller/config.php');

    $index = $_SESSION["index_number"];

    $sql = "SELECT * FROM admin WHERE index_number='$index'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $full_name = $row['full_name'];
    $i_name = $row['i_name'];
    $gender = $row['gender'];
    $address = $row['address'];
    $phone = $row['phone'];
    $email = $row['email'];
    $image = $row['image_name'];

    // Fetch user details
    $sql1 = "SELECT * FROM user WHERE email='$email'";
    $result1 = mysqli_query($conn, $sql1);
    $row1 = mysqli_fetch_assoc($result1);

    $user_name = $row1['email'];
    $hashed_password = $row1['password']; // Store the hashed password, do not display it
    ?>

    <section class="content"> 
        <div class="row">
            <div class="col-md-7">
                <div class="panel"><!--panel bg-maroon--> 
                    <div class="panel-heading bg-aqua-active">	
                        <h4 class="panel-title" id="hname"><?php echo $i_name; ?></h4>
                    </div>				
                    <div class="panel-body"><!--panel-body -->
                        <div class="row" id="my_profile">
                            <div class="col-md-3"> 
                                <img id="photo2" alt="User Pic" src="../<?php echo $image; ?>" class="img-circle img-responsive"> 
                            </div>
                            <div class="col-md-9"> 
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <td class="col-md-4">Full Name</td>
                                            <td id="full_name"><?php echo $full_name; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Name With Initials</td>
                                            <td id="i_name"><?php echo $i_name; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td id="address"><?php echo $address; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Gender</td>
                                            <td id="gender"><?php echo $gender; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td id="email"><?php echo $email; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Phone Number</td>
                                            <td id="phone"><?php echo $phone; ?></td>
                                        </tr>
                                        <tr>
                                            <td>User Name</td>
                                            <td id="user_name"><?php echo $user_name; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Password</td>
                                            <td id="password">**********</td> <!-- Mask the password -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <p class="alert-info" id="note1"><strong>Note: We get the email address for the user name.</strong></p>
                    </div>
                    <div class="panel-footer text-right" id="panel_footer">
                        <a href="#" onClick="editMyProfile('<?php echo $index; ?>')" type="button" class="btn btn-sm btn-warning" id="btnEdit"><i class="glyphicon glyphicon-edit"></i></a>
                        <input type="hidden" id="id1" value="">
                        <input type="hidden" id="i_path1" value="">
                        <span class="pull-right"></span>
                    </div>
                </div><!--/. panel--> 
            </div>
        </div><!--/.row --> 
    </section><!-- /.section -->
</div>
	
<script>
function editMyProfile(my_index){
    var xhttp = new XMLHttpRequest(); // MSK-000131-Start Ajax  
    xhttp.onreadystatechange = function() {		
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('my_profile').innerHTML = this.responseText; // MSK-000132
            var xhttp1 = new XMLHttpRequest(); // MSK-000131-Start Ajax  
            xhttp1.onreadystatechange = function() {		
                if (this.readyState == 4 && this.status == 200) {
                    var myArray = eval(xhttp1.responseText);
                    document.getElementById("id1").value = myArray[0];
                    document.getElementById("full_name1").value = myArray[1];
                    document.getElementById("i_name1").value = myArray[2];
                    document.getElementById("gender2").value = myArray[3];
                    document.getElementById("address1").value = myArray[4];
                    document.getElementById("phone1").value = myArray[5]; // Assuming this is in a normalized format
                    document.getElementById("email1").value = myArray[6];
                    document.getElementById("profile_pic1").src = "../" + myArray[7];
                    document.getElementById("user_name1").innerHTML = myArray[8];
                    document.getElementById("password1").value = myArray[9];

                    $("#panel_footer").hide();
                    $("#note1").hide();

                    $('[type="file"]').change(function (){
                        var fileSize = this.files[0].size;	
                        var maxSize = 1000000; // bytes
                        var ext = $('#fileToUpload').val().split('.').pop().toLowerCase();
                        
                        if ($.inArray(ext, ['png', 'jpg', 'jpeg']) == -1) {
                            profile_pic1.src = "../uploads/error.png"; 
                            $("#btnUpdate").attr("disabled", true);
                            $('#divPhoto').addClass('has-error has-feedback');
                            $('#divPhoto').append('<span id="spanPhoto" class="glyphicon glyphicon-remove form-control-feedback msk-set-width-tooltip" data-toggle="tooltip" title="The file type is not allowed"></span>');
                        } else if (fileSize > maxSize) {
                            profile_pic1.src = "../uploads/error.png"; 
                            $("#btnUpdate").attr("disabled", true);
                            $('#divPhoto').addClass('has-error has-feedback');
                            $('#divPhoto').append('<span id="spanPhoto1" class="glyphicon glyphicon-remove form-control-feedback msk-set-width-tooltip" data-toggle="tooltip" title="The file size is too large"></span>');		
                        } else {
                            profile_pic1.src = URL.createObjectURL(this.files[0]);	
                            $('#divPhoto').removeClass('has-error has-feedback');
                            $('#spanPhoto').remove();
                            $('#spanPhoto1').remove();
                            $("#btnUpdate").attr("disabled", false);
                            $("#i_path1").val(profile_pic1.src);
                        }
                    });

                    $("form").submit(function (e) {
                        var full_name = document.getElementById("full_name1").value;
                        var i_name = document.getElementById("i_name1").value;
                        var address = document.getElementById("address1").value;
                        var phone = document.getElementById("phone1").value.replace(/\D/g, ''); // Normalize phone number
                        var email = document.getElementById("email1").value;
                        var password = document.getElementById("password1").value;

                        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;	
                        var telformat = /^\d{10}$/; // Adjusted for normalized 10-digit phone number

                        if (full_name === '' || i_name === '' || address === '' || phone === '' || email === '' || password === '') {
                            $("#btnUpdate").attr("disabled", true);
                            e.preventDefault();
                            return false;
                        }

                        if (!mailformat.test(email)) {
                            $('#tdEmail2').addClass('has-error has-feedback');
                            $('#tdEmail2').append('<span id="spanEmail" class="glyphicon glyphicon-remove form-control-feedback msk-set-color-tooltip" data-toggle="tooltip" title="Enter valid email address"></span>');
                            $("#btnUpdate").attr("disabled", true);
                            e.preventDefault();
                            return false;
                        }

                        if (!telformat.test(phone)) {
                            $('#tdPhone2').addClass('has-error has-feedback');
                            $('#tdPhone2').append('<span id="spanPhone" class="glyphicon glyphicon-remove form-control-feedback msk-set-color-tooltip" data-toggle="tooltip" title="Enter valid phone number"></span>');
                            $("#btnUpdate").attr("disabled", true);
                            e.preventDefault();
                            return false;
                        }

                        // At this point, all validations passed. You may hash the password here if needed on the server side.
                    });
                }
            };
            xhttp1.open("GET", "../model/get_admin_profile.php?my_index=" + my_index , true);												
            xhttp1.send(); // MSK-000131-End Ajax
        }
    };
    xhttp.open("GET", "my_profile_update_form.php", true);												
    xhttp.send(); // MSK-000131-End Ajax
}
</script>
   		
</div><!-- /.content-wrapper -->  
              
<?php include_once('footer.php');?>	