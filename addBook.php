<?php
//Add book details after submit button is pressed.
include "dataconnection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data

    $title = $_POST["title"];
    $author = $_POST["author"];
    $publisher = $_POST["publisher"];
    $publication_year = $_POST["publication_year"];
    $isbn = $_POST["isbn"];

    $query = "INSERT INTO books (title, author, publisher, publication_year, isbn, available) 
        VALUES ('$title', '$author', '$publisher', '$publication_year', '$isbn', 1)";

    $result = mysqli_query($connect,$query);
    if ($result) {
        echo "
        <script>
            alert('Book has successfully added.');
            window.location.href = 'index.php';
        </script>
        ";
    }
    else {
        echo "
        <script>
        alert('There is wrong information.Try again');
        window.location.href = 'index.php';
        </script>
        ";
    }
};

?>
