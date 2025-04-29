<?php
session_start();
require 'dataconnection.php';
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
        .search {
            margin-top: 20px;
        }
        .search input[type="text"] {
            width: 70%;
            display: inline-block;
        }
        .search button {
            width: 20%;
            display: inline-block;
        }
        .pagination {
            justify-content: center;
        }
        .book-row {
            display: none;
        }
        #addBook, #editBook {
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
        .btn-edit, .btn-delete {
            width: 80px;
            padding: 10px;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .btn-edit {
            background-color: #17a2b8;
            color: white;
            border: none;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        .btn-edit:hover, .btn-delete:hover {
            opacity: 0.8;
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
        .table-danger {
            background-color: #f8d7da;
        }
        .table-warning {
            background-color: #fff3cd;
        }
    </style>
    <script>
        function toggleAddBookForm() {
            var form = document.getElementById("addBook");
            var otherForm = document.getElementById("editBook");
            if (form.style.display === "none" || form.style.display === "") {
                form.style.display = "block";
                otherForm.style.display = "none";
            } else {
                form.style.display = "none";
            }
        }

        function toggleEditBookForm() {
            var form = document.getElementById("editBook");
            var otherForm = document.getElementById("addBook");
            if (form.style.display === "block" || form.style.display === "") {
                form.style.display = "none";
            } else {
                form.style.display = "block";
                otherForm.style.display = "none";
            }
        }

        function editBook(editBookId) {
            const url = new URL(window.location.href);
            const searchParams = new URLSearchParams(url.search);
            searchParams.set('editBookId', editBookId);
            url.search = searchParams.toString();
            window.location.href = url.toString();
        }

        function confirmDeletion() {
            return confirm('Are you sure you want to delete this book?');
        }

        function printDiv(divId) {
            var content = document.getElementById(divId).innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = content;
            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        }


    </script>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="index.php">Library Management System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#bookInfo">Book Information</a>
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
        <div id="divSearch" class="divSection">
            <form action="index.php" method="get">
                <div class="search">
                    <h1>Book Information</h1>
                    <div class="form-group">
                        <label for="search_term">Search term:</label>
                        <input type="text" class="form-control" id="search_term" name="search_term">
                    </div>
                    <div class="form-group">
                        <label>Search by:</label><br>
                        <input type="radio" name="search_type" value="title" checked> Title
                        <input type="radio" name="search_type" value="author"> Author
                        <input type="radio" name="search_type" value="publisher"> Publisher
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>

        <div id="bookInfo">
            <div id="divResult" class="divSection">
                <?php
                function showTable($query, $header) {
                    global $connect;
                    $queryResult = mysqli_query($connect, $query);
                    if (!isset($_GET['editBookId'])) $editBookId = 0;
                    else $editBookId = $_GET['editBookId'];

                    echo "<div class='table-responsive'>";
                    echo "<h3>$header</h3>";
                    if ($queryResult && mysqli_num_rows($queryResult) > 0) {
                        echo "<table class='table table-bordered table-striped'>";
                        echo "<thead class='thead-dark'>";
                        echo "<tr>";
                        echo "<th>Book ID</th>";
                        echo "<th>Title</th>";
                        echo "<th>Author</th>";
                        echo "<th>Publisher</th>";
                        echo "<th>Publication Year</th>";
                        echo "<th>ISBN</th>";
                        echo "<th>Actions</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        while ($row = mysqli_fetch_assoc($queryResult)) {
                            $book_id = $row['book_id'];
                            echo "<tr>";
                            echo "<td>" . $book_id . "</td>";
                            echo "<td>" . $row['title'] . "</td>";
                            echo "<td>" . $row['author'] . "</td>";
                            echo "<td>" . $row['publisher'] . "</td>";
                            echo "<td>" . $row['publication_year'] . "</td>";
                            echo "<td>" . $row['isbn'] . "</td>";
                            echo "<td>";
                            echo "<button class='btn btn-info btn-edit' onclick='editBook($book_id)' " . ($editBookId == $book_id ? "disabled" : "") . ">Edit</button> ";
                            echo "
                            <form id='deleteForm' action='deleteBook.php' method='POST' style='display:inline;' onsubmit='return confirmDeletion()'>
                                <input type='hidden' name='book_id' value='$book_id'>
                                <button class='btn btn-danger btn-delete' type='submit'>Delete</button>
                            </form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<p>No results found.</p>";
                    }
                    echo "</div>";
                }

                if (isset($_GET["search_term"]) && isset($_GET["search_type"])) {
                    $searchTerm = $_GET["search_term"];
                    $searchType = $_GET["search_type"];
                    
                    $headerString = ($searchTerm == "" ? "Displaying all books" : "Displaying all $searchType containing \"$searchTerm\":");
                    showTable("SELECT * FROM books WHERE $searchType LIKE '%$searchTerm%'", $headerString);

                } else {
                    showTable("SELECT * FROM books WHERE available=1", "Displaying all books.");
                }

                if (isset($_GET["editBookId"]) && preg_match('/\d+/', $_GET['editBookId'])) {
                    $editBookId = $_GET['editBookId'];
                    $editQuery = "SELECT * FROM books WHERE book_id = $editBookId";

                    $queryResult = mysqli_query($connect, $editQuery);
                    $row = mysqli_fetch_assoc($queryResult);
                    echo "<button class='btn btn-secondary' onclick='toggleEditBookForm()'>Edit Book</button>";
                    echo "<form id='editBook' class='edit' action='editBook.php' method='post'>";
                    echo "
                        <p>Edit Book Details</p>
                        <table class='table'>
                        <tr>
                            <th>ID:</th>
                            <td><input class='form-control' type='text' name='id' readonly value={$editBookId}></td>
                        </tr>
                        <tr>
                            <th>Title:</th>
                            <td><input class='form-control' type='text' name='title' value='{$row['title']}'></td>
                        </tr>
                        <tr>
                            <th>Author:</th>
                            <td><input class='form-control' type='text' name='author' value='{$row['author']}'></td>
                        </tr>
                        <tr>
                            <th>Publisher:</th>
                            <td><input class='form-control' type='text' name='publisher' value='{$row['publisher']}'></td>
                        </tr>
                        <tr>
                            <th>Publication Year:</th>
                            <td><input class='form-control' type='number' name='publication_year' value='{$row['publication_year']}'></td>
                        </tr>
                        <tr>
                            <th>ISBN:</th>
                            <td><input class='form-control' type='text' name='isbn' value='{$row['isbn']}'></td>
                        </tr>
                        <tr>
                            <td><input class='btn btn-primary' type='submit' value='Submit'> <input class='btn btn-secondary' type='reset'></td>
                        </tr>
                    </table>
                    </form>";
                }
                ?>
            </div>
        </div>

        <div id="divAction" class="divSection">
            <button class="btn btn-success" onclick="toggleAddBookForm()">Add Book</button>
        </div>

        <form id="addBook" class="divSection" action="addBook.php" method="post">
            <p>Add Book Details</p>
            <table class="table">
                <tr>
                    <th>Title:</th>
                    <td><input class="form-control" type="text" name="title" required></td>
                </tr>
                <tr>
                    <th>Author:</th>
                    <td><input class="form-control" type="text" name="author" required></td>
                </tr>
                <tr>
                    <th>Publisher:</th>
                    <td><input class="form-control" type="text" name="publisher" required></td>
                </tr>
                <tr>
                    <th>Publication Year:</th>
                    <td><input class="form-control" type="number" name="publication_year" required></td>
                </tr>
                <tr>
                    <th>ISBN:</th>
                    <td><input class="form-control" type="text" name="isbn" required></td>
                </tr>
                <tr>
                    <td><input class="btn btn-primary" type="submit" value="Submit"> <input class="btn btn-secondary" type="reset"></td>
                </tr>
            </table>
        </form>

        <div id="borrowInfo">
            <div class="divSection">
                <?php
                function showBorrowTable($query, $header) {
                    global $connect;
                    $queryResult = mysqli_query($connect, $query);

                    $records = [];

                    if ($queryResult && mysqli_num_rows($queryResult) > 0) {
                        while ($row = mysqli_fetch_assoc($queryResult)) {
                            $borrow_id = $row['borrow_id'];
                            $returnDate = new DateTime($row['return_date']);
                            $currentDate = new DateTime();
                            $interval = $currentDate->diff($returnDate)->days;

                            if ($currentDate > $returnDate) {
                                $records[] = array_merge($row, ['status' => 'Expired']);
                            } elseif ($interval <= 3) {
                                $records[] = array_merge($row, ['status' => 'Urgent']);
                            } else {
                                $records[] = array_merge($row, ['status' => 'Normal']);
                            }
                        }
                    }

                    echo "<div class='borrow-table-responsive'>";
                    echo "<h3>$header</h3>";
                    
                    if (count($records) > 0) {
                        echo "<table class='borrow-table table-bordered table-striped'>";
                        echo "<thead class='thead-dark'>";
                        echo "<tr>";
                        echo "<th>Borrow ID</th>";
                        echo "<th>Borrower Name</th>";
                        echo "<th>Borrower NRIC</th>";
                        echo "<th>Borrow Date</th>";
                        echo "<th>Return Date</th>";
                        echo "<th>Book ID</th>";
                        echo "<th>Status</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        foreach ($records as $row) {
                            $statusClass = '';
                            if ($row['status'] === 'Expired') {
                                $statusClass = 'table-danger';
                            } elseif ($row['status'] === 'Urgent') {
                                $statusClass = 'table-warning';
                            }

                            echo "<tr class='$statusClass'>";
                            echo "<td>" . $row['borrow_id'] . "</td>";
                            echo "<td>" . $row['borrower_name'] . "</td>";
                            echo "<td>" . $row['borrower_nric'] . "</td>";
                            echo "<td>" . $row['borrow_date'] . "</td>";
                            echo "<td>" . $row['return_date'] . "</td>";
                            echo "<td>" . $row['book_id'] . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo "<p>No records found.</p>";
                    }

                    echo "</div>";
                }

                showBorrowTable("SELECT * FROM borrow", "Displaying all borrow records.");
                ?>
                 <div class="text-center">
                 <button onclick="printDiv('borrowInfo')" class="btn btn-primary">Print</button>
                 </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>