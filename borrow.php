<?php
session_start();
require 'dataconnection.php';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = $_POST['book_id'];
    $action = $_POST['action'];
    $borrower_name = $_POST['borrower_name'] ?? null;
    $borrower_nric = $_POST['borrower_nric'] ?? null;

    if ($action == 'borrow') {
        $borrow_date = date('Y-m-d');
        $return_date = date('Y-m-d', strtotime('+2 weeks'));

        $stmt = $conn->prepare("UPDATE books SET available = FALSE WHERE book_id = ?");
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO borrow (borrower_name, borrower_nric, borrow_date, return_date, book_id, returned) VALUES (?, ?, ?, ?, ?, FALSE)");
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);
        }
        
        $stmt->bind_param("sissi", $borrower_name, $borrower_nric, $borrow_date, $return_date, $book_id);
        $stmt->execute();
        if ($stmt === false) {
            die('MySQL execute error: ' . $stmt->error);
        }
        $stmt->close();
        
    } elseif ($action == 'return') {
        $return_date = date('Y-m-d');

        $stmt = $conn->prepare("UPDATE books SET available = TRUE WHERE book_id = ?");
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE borrow SET returned = TRUE, return_date = ? WHERE book_id = ? AND returned = FALSE");
        $stmt->bind_param("si", $return_date, $book_id);
        $stmt->execute();
        $stmt->close();
    }
}

$sql = "SELECT book_id, title, author, available FROM books";
$result = $conn->query($sql);
?>

<?php

if (!isset($_SESSION['Admin_ID'])) {
    header("Location: login.php");
    exit();
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            padding-top: 65px;
        }

        .pagination {
            justify-content: center;
        }
        .book-row {
            display: none;
        }

        .container {
            margin-top: 20px;
        }
        .divSection {
            margin: 20px 0;
        }
        .table th, .table td {
            vertical-align: middle;
        }

        .borrow-table-responsive {
            overflow-x: auto;
            margin-bottom: 2rem;
        }
        .borrow-table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: white;
            color: black;
        }
        .borrow-table-bordered {
            border: 1px solid #dee2e6;
        }
        .borrow-table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0,0,0,.05);
        }
        .borrow-table .thead-dark th {
            color: black;
            background-color: white;
            border-color: #dee2e6;
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="home.php">Library Management System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="borrow.php">Borrow / Return Book</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

<div class="container">
        <div class="divSection">
            <h1>Library Book List</h1>
            <table class='table table-bordered table-striped'>
            <thead class='thead-dark'>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['author'] . "</td>";
        echo "<td>" . ($row['available'] ? 'Available' : 'Borrowed') . "</td>";
        echo "<td>";
        if ($row['available']) {
            echo "<form method='POST' class='borrow-form' style='display:inline;'>
                    <input type='hidden' name='book_id' value='" . $row['book_id'] . "'>
                    <input type='hidden' name='action' value='borrow'>
                    <input type='text' name='borrower_name' class='form-control' placeholder='Name' required>
                    <input type='text' name='borrower_nric' class='form-control' placeholder='NRIC' required>
                    <button type='submit' class='btn btn-primary btn-sm'>Borrow</button>
                  </form>";
        } else {
            echo "<form method='POST' class='return-form' style='display:inline;'>
                    <input type='hidden' name='book_id' value='" . $row['book_id'] . "'>
                    <input type='hidden' name='action' value='return'>
                    <button type='submit' class='btn btn-danger btn-sm'>Return</button>
                  </form>";
        }
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No books available</td></tr>";
}
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
