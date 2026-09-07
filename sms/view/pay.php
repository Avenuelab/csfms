<?php
session_start(); // Start the session

include_once('../controller/config.php'); // Include your database connection file

// Initialize variables
$index_number = isset($_POST['index_number']) ? $_POST['index_number'] : '';
$total_fee = 0.00; 
$paid_amt = 0.00;   
$balance = 0.00;    
$month = date('F');
$year = date('Y');
$date = date('Y-m-d'); // Current date in Y-m-d format
$invoice_number = rand(1000, 9999); 
$status = 'Unpaid'; 
$message = '';
$search_date = isset($_POST['search_date']) ? $_POST['search_date'] : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['search_student'])) {
        $index_number = $_POST['index_number'];
        $total_fee = $_POST['total_fee']; // Get total fee from input

        // Fetch student details by index number
        $sql_student = "SELECT * FROM student WHERE index_number = ?";
        $stmt_student = $conn->prepare($sql_student);
        $stmt_student->bind_param("s", $index_number);
        $stmt_student->execute();
        $student_result = $stmt_student->get_result();

        // Check if student exists
        if ($student_result->num_rows > 0) {
            // Fetch student data
            $student_data = $student_result->fetch_assoc();
            
            if (isset($_POST['paid_amt']) && $_POST['paid_amt'] !== '') {
                $paid_amt = $_POST['paid_amt'];

                // Prevent entry of amount = 0
                if ($paid_amt <= 0) {
                    $message = "The paid amount must be greater than zero.";
                } else {
                    $balance = $total_fee - $paid_amt;
                    $status = ($balance <= 0) ? 'Paid' : 'Unpaid';

                    // Prepare SQL statement for student_payment_history
                    $sql_history = "INSERT INTO student_payment_history 
                                    (index_number, total_fee, subtotal, _status, month, year, date, invoice_number, paid_amt, balance) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt_history = $conn->prepare($sql_history);
                    $stmt_history->bind_param("iddssssiid", $index_number, $total_fee, $total_fee, $status, $month, $year, $date, $invoice_number, $paid_amt, $balance);

                    if ($stmt_history->execute()) {
                        $message = "Payment recorded successfully. Invoice Number: $invoice_number";
                    } else {
                        $message = "Error recording payment history: " . $stmt_history->error;
                    }
                    $stmt_history->close();
                }
            } else {
                $message = "Please enter a valid amount for 'paid_amt'.";
            }
        } else {
            $message = "No student found with the index number: $index_number.";
        }
        $stmt_student->close();
    }
}

// Fetch payment history for the given index number
if ($index_number) {
    // Prepare the query for fetching payment history
    $sql = "SELECT 
                id,
                index_number,
                total_fee,
                subtotal,
                _status,
                month,
                year,
                date,
                invoice_number,
                paid_amt,
                balance
            FROM 
                student_payment_history
            WHERE 
                index_number = ?";

    // If a search date is provided, modify the query
    if (!empty($search_date)) {
        $sql .= " AND date = ?";
    }
    
    $sql .= " ORDER BY date DESC"; // Order by date descending

    $stmt_history = $conn->prepare($sql);
    if (!empty($search_date)) {
        $stmt_history->bind_param("ss", $index_number, $search_date);
    } else {
        $stmt_history->bind_param("s", $index_number);
    }
    $stmt_history->execute();
    $result = $stmt_history->get_result();
} else {
    $result = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Record Student Payment</title>
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <style>
        .status-paid { color: green; }
        .status-unpaid { color: red; }
        .btn-home {
            background-color: #007bff; /* Bootstrap primary color */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }
        .btn-home:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
    </style>
    <script>
        function printInvoice(invoiceId) {
            window.open('print_invoice.php?id=' + invoiceId, '_blank', 'width=800,height=600');
        }
    </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="btn-home" href="dashboard.php">Home</a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <div class="content-wrapper">
        <section class="content">
            <h2>Record Student Payment</h2>

            <?php if (isset($message)) : ?>
                <div class="alert alert-info"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group">
                    <label for="index_number">Index Number:</label>
                    <input type="text" class="form-control" id="index_number" name="index_number" value="<?php echo htmlspecialchars($index_number); ?>" required>
                </div>
                <div class="form-group">
                    <label for="total_fee">Total Fee:</label>
                    <input type="number" step="0.01" class="form-control" id="total_fee" name="total_fee" value="<?php echo htmlspecialchars($total_fee); ?>" required>
                </div>
                <div class="form-group">
                    <label for="paid_amt">Amount Paid:</label>
                    <input type="number" step="0.01" class="form-control" id="paid_amt" name="paid_amt" value="<?php echo htmlspecialchars($paid_amt); ?>" required>
                </div>
                <button type="submit" name="search_student" class="btn btn-primary">Record Payment</button>
            </form>

            <h3>Payment History</h3>
            <form action="" method="post" class="mb-3">
                <div class="form-group">
                    <label for="search_date">Search by Date:</label>
                    <input type="date" class="form-control" id="search_date" name="search_date" value="<?php echo htmlspecialchars($search_date); ?>">
                </div>
                <button type="submit" class="btn btn-secondary">Search</button>
                <input type="hidden" name="index_number" value="<?php echo htmlspecialchars($index_number); ?>">
            </form>

            <?php if ($result && $result->num_rows > 0) : ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Invoice Number</th>
                            <th>Index Number</th>
                            <th>Total Fee</th>
                            <th>Subtotal</th>
                            <th>Status</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Date</th>
                            <th>Paid Amount</th>
                            <th>Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['invoice_number']; ?></td>
                                <td><?php echo htmlspecialchars($row['index_number']); ?></td>
                                <td>Ksh<?php echo number_format($row['total_fee'], 2); ?></td>
                                <td>Ksh<?php echo number_format($row['subtotal'], 2); ?></td>
                                <td class="<?php echo ($row['_status'] == 'Paid') ? 'status-paid' : 'status-unpaid'; ?>">
                                    <?php echo $row['_status']; ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['month']); ?></td>
                                <td><?php echo $row['year']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                                <td>Ksh<?php echo number_format($row['paid_amt'], 2); ?></td>
                                <td>Ksh<?php echo number_format($row['balance'], 2); ?></td>
                                <td>
                                    <button class="btn btn-secondary" onclick="printInvoice(<?php echo $row['invoice_number']; ?>)">Print Invoice</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No payment records found for this index number.</p>
            <?php endif; ?>
        </section>
    </div>
</div>
<?php include_once('footer.php'); ?>
</body>
</html>