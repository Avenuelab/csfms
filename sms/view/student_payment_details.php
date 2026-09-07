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

// Check if index is set
$index = isset($_GET['index']) ? $_GET['index'] : null;

if ($index === null) {
    die("Error: Index number is required.");
}

$sql = "SELECT * FROM student WHERE index_number='$index'";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Error: No student found with the provided index number.");
}

$row = mysqli_fetch_assoc($result);

$image_name = $row['image_name'] ?? 'default_image.png'; // Fallback to a default image
$full_name = $row['full_name'] ?? 'N/A';
$i_name = $row['i_name'] ?? 'N/A';
$address = $row['address'] ?? 'N/A';
$phone = $row['phone'] ?? 'N/A';
$email = $row['email'] ?? 'N/A';
?>                    
<div class="modal msk-fade" id="modalviewPayment" tabindex="-1" role="dialog" aria-labelledby="insert_alert1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog"><!--modal-dialog -->  
        <div class="container"><!--modal-content --> 
            <div class="row">
                <div class="col-md-6">
                    <div class="panel"><!--panel bg-maroon--> 
                        <div class="panel-heading bg-aqua-active">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                            <h4 class="panel-title" id="hname"><?php echo htmlspecialchars($i_name); ?></h4>
                        </div>
                        <div class="panel-body"><!--panel-body -->
                            <div class="row">
                                <div class="col-md-3"> 
                                    <img id="photo2" alt="User Pic" src="../<?php echo htmlspecialchars($image_name); ?>" class="img-circle img-responsive"> 
                                </div>
                                <div class="col-md-9"> 
                                    <table class="table table-user-information">
                                        <tbody>
                                            <tr>
                                                <td>Full Name</td>
                                                <td id="full_name3"><?php echo htmlspecialchars($full_name); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Address</td>
                                                <td id="address3"><?php echo htmlspecialchars($address); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Phone</td>
                                                <td id="phone3"><?php echo htmlspecialchars($phone); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td id="email3"><?php echo htmlspecialchars($email); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Last Payment</td>
<?php
$last_month = date('F', strtotime('-1 months'));
$current_month = date('F');    

$sql1 = "SELECT paid FROM student_payment WHERE index_number='$index' AND month='$last_month' AND (_status='Monthly Fee' OR _status='Monthly Fee1')";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($result1);

$last_paid = isset($row1['paid']) ? $row1['paid'] : 0;
$last_paid = number_format($last_paid, 2, '.', '');
?>                                            
                                                <td id="lastMonth"><?php echo htmlspecialchars($last_month); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Monthly Fee</td>
                                                <td id="mFee_lMonth">$<?php echo $last_paid; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Paid</td>
                                                <td id="lastPaid">$<?php echo $last_paid; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Current Month</td>
                                                <td><?php echo htmlspecialchars($current_month); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Monthly Fee</td>
<?php
$current_year = date('Y'); 
$monthly_fee = 0;

$sql = "SELECT subject_routing.fee AS s_fee 
        FROM student_subject
        INNER JOIN subject_routing ON student_subject.sr_id = subject_routing.id 
        WHERE student_subject.index_number='$index' AND year='$current_year'";

$result = mysqli_query($conn, $sql);
    
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $monthly_fee += $row['s_fee'];
    }
    $monthly_fee = number_format($monthly_fee, 2, '.', '');
}
?>                                            
                                                <td id="current_mFee">$<?php echo htmlspecialchars($monthly_fee); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Paid</td>
<?php
$sql1 = "SELECT paid FROM student_payment WHERE index_number='$index' AND month='$current_month' AND (_status='Monthly Fee' OR _status='Monthly Fee1')";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($result1);
$paid_current_month = isset($row1['paid']) ? $row1['paid'] : 0;

$due = $monthly_fee - $paid_current_month;
$due = number_format($due, 2, '.', '');
?>                                            
                                                <td id="currentPaid">$<?php echo $paid_current_month; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Due Amount</td>
                                                <td id="dueAmount">$<?php echo $due; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!--/.panel-body -->
                        <div class="panel-footer bg-gray-light"><!--panel-footer-->
                            <a data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>
                            <span class="pull-right">
                                <input type="hidden" id="index_number" value="<?php echo htmlspecialchars($index); ?>">
                                <input type="hidden" id="cpage" value="<?php echo htmlspecialchars($_GET['page'] ?? '1'); ?>">
                                <button id="btnPaid" onClick="addPayment(this)" class="btn btn-success">+Add Payment</button>
                            </span>
                        </div><!--/.panel-footer-->
                    </div><!--/.panel--> 
                </div>
            </div><!--/.row --> 
        </div><!--/.modal-content -->
    </div><!--/.modal-dialog -->
</div><!--/.modal -->