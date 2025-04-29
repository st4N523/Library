<?php
//Add book details after submit button is pressed.
include "dataconnection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $id = $_POST["id"];
    $title = $_POST["title"];
    $author = $_POST["author"];
    $publisher = $_POST["publisher"];
    $publication_year = $_POST["publication_year"];
    $isbn = $_POST["isbn"];
    $sql = "UPDATE books 
                SET title = '$title', author = '$author', publisher = '$publisher', publication_year = '$publication_year', isbn = '$isbn'
                WHERE book_id = '$id'";

    $result = mysqli_query($connect,$sql);
    if ($result) {
        echo "
        <script>
            alert('Book has successfully Edit.');
            window.location.href = 'index.php';
        </script>
        ";
    }
    else {
        echo "
        <script>
        alert('Something is wrong.');
        window.location.href = 'index.php';
        </script>
        ";
    }
};

?>
