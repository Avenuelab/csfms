<?php
if (!isset($_SERVER['HTTP_REFERER'])) {
    header('location:../index.php');
    exit;
}

include_once('../controller/config.php');

// Check and create teacher_attendants table if it doesn't exist
$tableCheckQuery = "CREATE TABLE IF NOT EXISTS teacher_attendants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    index_number VARCHAR(50) NOT NULL,
    attended_date DATE NOT NULL,
    status ENUM('Present', 'Absent', 'Late') NOT NULL
)";

if (!mysqli_query($conn, $tableCheckQuery)) {
    die("Error creating table: " . mysqli_error($conn));
}

?>

<div class="col-md-8">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">All Teacher</h3>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <th class="col-md-1">ID</th>
                    <th class="col-md-3">Name</th>
                    <th class="col-md-4">Action</th>
                </thead>
                <tbody>

<?php
$sql = "SELECT * FROM teacher";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

$count = 0;

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $count++;
        $id = $row['id'];
        $index = $row['index_number'];

        echo '<tr>';
        echo '<td>' . $count . '</td>';
        echo '<td id="td1_' . $row['id'] . '">' . $row['i_name'] . '</td>';
        echo '<td>';

        // Check for dependencies before allowing delete
        $cant_remove = 0;

        // Check various tables for records related to the teacher
        $tables = [
            "subject_routing" => "teacher_id='$id'",
            "timetable" => "teacher_id='$id'",
            "teacher_attendants" => "index_number='$index'",
            "teacher_salary" => "index_number='$index'",
            "teacher_salary_history" => "index_number='$index'",
            "my_friends" => "friend_index='$index'",
            "online_chat" => "user_index='$index' AND user_type='Teacher'",
            "petty_cash" => "received_by='$index' AND received_type='Teacher'",
            "petty_cash_history" => "received_by='$index' AND received_type='Teacher'",
            "events" => "create_by='$index' AND creator_type='Teacher'"
        ];

        foreach ($tables as $table => $condition) {
            $sql_check = "SELECT * FROM $table WHERE $condition";
            $result_check = mysqli_query($conn, $sql_check);
            if ($result_check && mysqli_num_rows($result_check) > 0) {
                $cant_remove++;
            }
        }

        // Determine actions based on dependencies
        if ($cant_remove > 0) {
            echo '<a href="#modalUpdateform" onClick="showModal(this)" class="btn btn-info btn-xs" data-id="' . $id . '" data-toggle="modal">Edit</a>';
            echo ' <a href="#" onClick="addSalary(this)" class="btn btn-success btn-xs" data-id="' . $index . ',' . $id . '">Add Salary</a>';
            echo ' <a href="#" onClick="viewPayments(this)" class="btn btn-info btn-xs" data-id="' . $index . '">View Payments</a>';
        } else {
            echo '<a href="#modalUpdateform" onClick="showModal(this)" class="btn btn-info btn-xs" data-id="' . $id . '" data-toggle="modal">Edit</a>';
            echo ' <a href="#" class="confirm-delete btn btn-danger btn-xs" data-id="' . $id . '">Delete</a>';
            echo ' <a href="#" onClick="addSalary(this)" class="btn btn-success btn-xs" data-id="' . $index . ',' . $id . '">Add Salary</a>';
            echo ' <a href="#" onClick="viewPayments(this)" class="btn btn-info btn-xs" data-id="' . $index . '">View Payments</a>';
        }

        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="3">No teachers found.</td></tr>';
}
?>
                </tbody>
            </table>
        </div>
    </div>
</div>