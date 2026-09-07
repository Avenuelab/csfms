<?php
session_start(); // Start the session

include_once('../controller/config.php'); // Include your database connection file

// Initialize variables
$expense_category = '';
$amount = '';
$date = date('Y-m-d');
$description = '';
$message = '';

// Handle form submission to add expenses
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_expense'])) {
    $expense_category = $_POST['expense_category'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    // Check if the expense already exists
    $check_sql = "SELECT * FROM expenses WHERE expense_category = ? AND amount = ? AND date = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("sds", $expense_category, $amount, $date);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = "This expense already exists for the given details.";
    } else {
        // Prepare SQL statement to insert expense
        $sql = "INSERT INTO expenses (expense_category, amount, date, description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdss", $expense_category, $amount, $date, $description);

        if ($stmt->execute()) {
            $message = "Expense added successfully!";
            echo '<meta http-equiv="refresh" content="2;URL=dashboard.php">';
        } else {
            $message = "Error adding expense: " . $stmt->error;
        }
        $stmt->close();
    }
    $check_stmt->close();
}

// Fetch all expenses
$sql = "SELECT * FROM expenses ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>School Expenses</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
</head>
<body>
<div class="container mt-5">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="btn-home" href="dashboard.php">Home</a>
            </li>
        </ul>
    </nav>
    <h2>School Expenses</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="" method="post" class="mb-4">
        <div class="form-group">
            <label for="expense_category">Expense Category:</label>
            <input type="text" class="form-control" id="expense_category" name="expense_category" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
        </div>
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" id="date" name="date" value="<?php echo $date; ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <button type="submit" name="add_expense" class="btn btn-primary">Add Expense</button>
    </form>

    <h3>Expense Records</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Description</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['expense_category']); ?></td>
                        <td>Ksh<?php echo number_format($row['amount'], 2); ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No expenses recorded.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>