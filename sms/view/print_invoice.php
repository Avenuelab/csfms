<?php
include_once('../controller/config.php'); // Include your database connection file

if (isset($_GET['id'])) {
    $invoice_number = $_GET['id'];

    // Fetch invoice details based on invoice number
    $sql = "SELECT 
                index_number,
                total_fee,
                subtotal,
                _status,
                month,
                year,
                date,
                paid_amt,
                balance
            FROM 
                student_payment_history 
            WHERE 
                invoice_number = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $invoice_number);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $invoice = $result->fetch_assoc();
    } else {
        echo "No invoice found.";
        exit;
    }
    $stmt->close();
} else {
    echo "Invalid invoice number.";
    exit;
}
?>

<?php include_once('head.php'); ?>

<body>

    <div class="content-wrapper">
        <section class="content">
    <h2>Invoice</h2>
    <p><strong>Index Number:</strong> <?php echo htmlspecialchars($invoice['index_number']); ?></p>
    <p><strong>Total Fee:</strong> Ksh<?php echo number_format($invoice['total_fee'], 2); ?></p>
    <p><strong>Subtotal:</strong> Ksh<?php echo number_format($invoice['subtotal'], 2); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($invoice['_status']); ?></p>
    <p><strong>Month:</strong> <?php echo htmlspecialchars($invoice['month']); ?></p>
    <p><strong>Year:</strong> <?php echo $invoice['year']; ?></p>
    <p><strong>Date:</strong> <?php echo $invoice['date']; ?></p>
    <p><strong>Paid Amount:</strong> Ksh<?php echo number_format($invoice['paid_amt'], 2); ?></p>
    <p><strong>Balance:</strong> Ksh<?php echo number_format($invoice['balance'], 2); ?></p>
    
    <button class="btn btn-primary" onclick="window.print()">Print Invoice</button>
</section>
</div>

<script src="path/to/bootstrap.bundle.min.js"></script> <!-- Adjust the path -->
<?php include_once('footer.php');?>
</body>
</html>