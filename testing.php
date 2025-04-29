<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Information</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        #addBook {
            display: none;
        }
        #editBook {
            display: block;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination button {
            margin: 0 5px;
            padding: 10px 20px;
        }
        .book-row {
            display: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let currentPage = 1;
            const resultsPerPage = 10;
            const bookRows = document.querySelectorAll('.book-row');
            const totalPages = Math.ceil(bookRows.length / resultsPerPage);

            function showPage(page) {
                const start = (page - 1) * resultsPerPage;
                const end = start + resultsPerPage;
                bookRows.forEach((row, index) => {
                    row.style.display = (index >= start && index < end) ? 'table-row' : 'none';
                });
                document.getElementById('currentPage').textContent = page;
            }

            document.getElementById('prevPage').addEventListener('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    showPage(currentPage);
                }
            });

            document.getElementById('nextPage').addEventListener('click', function () {
                if (currentPage < totalPages) {
                    currentPage++;
                    showPage(currentPage);
                }
            });

            showPage(currentPage);
        });
    </script>
    <script>
        function toggleAddBookForm() {
            //Add book button handler 
            var form = document.getElementById("addBook");
            var otherForm = document.getElementById("editBook");
            if (form.style.display === "none" || form.style.display == "") {
                form.style.display = "block";
                otherForm.style.display = "none";
            } else {
                form.style.display = "none";
            }
        }
        function toggleEditBookForm() {
            //Edit book button handler
            var form = document.getElementById("editBook");
            var otherForm = document.getElementById("addBook");
            if (form.style.display === "block" || form.style.display == "") {
                form.style.display = "none";
            } else {
                form.style.display = "block";
                otherForm.style.display = "none";
            }
        }

		function htmlScroll(){
			var top = document.body.scrollTop || document.documentElement.scrollTop;
			if(elFix.data_top<top){
				elFix.style.position='fixed';
				elFix.style.top=1;
				elFix.style.left=elFix.data_left;
			}else{
				elFix.style.position='static';
			}
		}
		function htmlPosition(obj){
			var o=obj;
			var t=o.offsetTop;
			var l=o.offsetLeft;
			while(o=o.offsetParent){
				t += o.offsetTop;
				l += o.offsetLeft;
			}
			obj.data_top=t;
			obj.data_left=l;
		}
		var oldHtmlWidth=document.documentElement.offsetWidth;
		window.onresize=function(){
			var newHtmlWidth=document.documentElement.offsetWidth;
			if(oldHtmlWidth==newHtmlWidth){
				return;
			}
			oldHtmlWidth=newHtmlWidth;
			elFix.style.position='static';
			htmlPosition(elFix);
			htmlScroll();
		}
		window.onscroll=htmlScroll;
		
		var elFix=document.getElementById('divSearch');
		htmlPosition(elFix);
	</script>
</head>
<body>
    <div class="headerBar">
        <table>
            <tr>
                <td colspan="4" style="font-size: 32px;">Library Management System</td>
                <td><a href="index.html">HOME</a></td>
                <td><a href="search.php"><i class="fa fa-book"></i>&nbsp;BOOK INFORMATION</a></td>
                <td><a href="borrow.php"><i class="fa fa-pencil"></i>&nbsp;BORROW / RETURN BOOK</a></td>
            </tr>
        </table>
    </div>

    <div id="divSearch" class="divSection">
        <form action="search.php" method="get">
            <table class="search">
                <tr>
                    <th><h1>Book Information</h1></th>
                </tr>
                <tr>
                    <th>Search term: &nbsp;&nbsp;
                        <input type="text" name="search_term">
                        <button type="submit">Submit</button>
                    </th>
                </tr>
                <tr>
                    <th>
                        <input type="radio" name="search_type" value="title" checked> Title
                        <input type="radio" name="search_type" value="author"> Author
                        <input type="radio" name="search_type" value="publisher"> Publisher
                    </th>
                </tr>
            </table>
        </form>
    </div>

    <div id="divResult" class="divSection">
        <?php
        require 'dataconnection.php';

        function showTable($query, $header) {
            global $connect;
            $result = mysqli_query($connect, $query);
            if (!isset($_GET['editBookId'])) $editBookId = 0;
            else $editBookId = $_GET['editBookId'];

            echo "$header<br>";
            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table class='result'>";
                echo "<tr>";
                echo "<th>Book ID</th>";
                echo "<th>Title</th>";
                echo "<th>Author</th>";
                echo "<th>Publisher</th>";
                echo "<th>Publication Year</th>";
                echo "<th>ISBN</th>";
                echo "<th>Links</th>";
                echo "</tr>";

                while ($row = mysqli_fetch_assoc($result)) {
                    $book_id = $row['book_id'];
                    echo "<tr class='book-row'>";
                    echo "<td>" . $book_id . "</td>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['author'] . "</td>";
                    echo "<td>" . $row['publisher'] . "</td>";
                    echo "<td>" . $row['publication_year'] . "</td>";
                    echo "<td>" . $row['isbn'] . "</td>";
                    echo "<td>";
                    echo "<button onclick='editBook($book_id)'" . ($editBookId == $book_id ? "disabled" : "") . ">Edit Details</button> ";
                    echo "<button onclick='deleteBook($book_id)'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";

                echo "
                <script>
                function modifyQueryString(paramName, paramValue) {
                    const url = new URL(window.location.href);
                    const searchParams = new URLSearchParams(url.search);
                    searchParams.set(paramName, paramValue);
                    url.search = searchParams.toString();
                    window.location.href = url.toString();
                }

                function editBook(editBookId) {
                    modifyQueryString('editBookId', editBookId);
                }

                function deleteBook(book_id) {
                    if (confirm('Are you sure you want to delete this book?')) 
                    {
                        url = 'deleteBook.php?book_id=' + book_id;
                        window.location.href = url;
                    } 
                    else {
                        window.location.href = 'search.php';
                    }
                }
                </script>
                ";
            } else {
                echo "No results found.";
            }
        }

        if (isset($_GET["search_term"]) && isset($_GET["search_type"])) {
            $searchTerm = $_GET["search_term"];
            $searchType = $_GET["search_type"];
            $headerString = ($searchTerm == "" ? "Displaying all books" : "Displaying all $searchType containing \"$searchTerm\":");
            showTable("SELECT * FROM books WHERE $searchType LIKE '%$searchTerm%'", $headerString);
        } else {
            showTable("SELECT * FROM books", "Displaying all books.");
        }
        ?>
    </div>

    <div class="pagination">
        <button id="prevPage">Previous</button>
        <span id="currentPage">1</span>
        <button id="nextPage">Next</button>
    </div>

    <div id="divAction" class="divSection">
        <button onclick="toggleAddBookForm()">Add Book</button>
    </div>

    <form id="addBook" action="addBook.php" method="post">
        <p>Add Book Details</p>
        <table style="text-align: left;">
            <tr>
                <th><label for="title">Title:</label></th>
                <td><input type="text" name="title" required></td>
            </tr>
            <tr>
                <th><label for="author">Author:</label></th>
                <td><input type="text" name="author" required></td>
            </tr>
            <tr>
                <th><label for="publisher">Publisher:</label></th>
                <td><input type="text" name="publisher" required></td>
            </tr>
            <tr>
                <th><label for="publication_year">Publication Year:</label></th>
                <td><input type="" size="4" name="publication_year" required></td>
            </tr>
            <tr>
                <th><label for="isbn">ISBN:</label></th>
                <td><input type="text" name="isbn" required></td>
            </tr>
            <tr>
                <td><input type="submit" value="Submit"> <input type="reset"></td>
            </tr>
        </table>
    </form>
    <form id="addBook" action="addBook.php" method="post">
    <!-- Add book form -->
    <p>Add Book Details</p>
    <table style="text-align: left;">
        <tr>
            <th>
                <label for="title">Title:</label>
            </th>
            <td>
                <input type="text" name="title" required>
            </td>
        </tr>
        <tr>
            <th>
                <label for="author">Author:</label>
            </th>
            <td>
                <input type="text" name="author" required>
            </td>
        </tr>
        <tr>
            <th>
                <label for="publisher">Publisher:</label>
            </th>
            <td>
                <input type="text" name="publisher" required>
            </td>
        </tr>
        <tr>
            <th>
                <label for="publication_year">Publication Year:</label>
            </th>
            <td>
                <input type="" size=4 name="publication_year" required>
            </td>
        </tr>
        <tr>
            <th>
                <label for="isbn">ISBN:</label>
            </th>
            <td>
                <input type="text" name="isbn" required>
            </td>
        </tr>
        <tr>
            <td>
                <input type="submit" value="Submit"> <input type="reset">
            </td>
        </tr>
    </table>
</form>
</body>
</html>