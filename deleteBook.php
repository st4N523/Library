<?php
include "dataconnection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $id = $_POST["book_id"];
    
    $sql = "DELETE FROM books WHERE book_id = '$id'";

    $result = mysqli_query($connect, $sql);
    
    if ($result) {
        echo "
        <script>
            alert('Book has been successfully deleted.');
            window.location.href = 'index.php';
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Something went wrong.');
            window.location.href = 'index.php';
        </script>
        ";
    }
}
?>
