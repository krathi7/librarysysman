<?php
include('db.php'); // Assuming your database connection is in db.php

// Assume student is logged in and you have their student_id (e.g., from session or authentication)
$student_id = 1; // Example, replace with actual student ID from session

// Fetch available books with available copies
$books_query = "SELECT book_id, title, author, available_copies FROM books";
$books_result = $conn->query($books_query);

// Fetch student's books (both pending returns and those that have been returned)
$history_query = "SELECT t.transaction_id, b.title, t.issue_date, t.return_date
                  FROM transactions t
                  JOIN books b ON t.book_id = b.book_id
                  WHERE t.student_id = $student_id";
$history_result = $conn->query($history_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Student Dashboard</h1>

    <!-- Display Available Books -->
    <h2>Books Available in the Library</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Available Copies</th>
        </tr>
        <?php
        if ($books_result->num_rows > 0) {
            while ($row = $books_result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['title'] . "</td>
                        <td>" . $row['author'] . "</td>
                        <td>" . $row['available_copies'] . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No books available.</td></tr>";
        }
        ?>
    </table>

    <!-- Display Student's Reading History (including returned books) -->
    <h2>Your Reading History</h2>
    <table>
        <tr>
            <th>Book Title</th>
            <th>Issue Date</th>
            <th>Return Date</th>
        </tr>
        <?php
        if ($history_result->num_rows > 0) {
            while ($row = $history_result->fetch_assoc()) {
                $return_date = $row['return_date'] ? $row['return_date'] : "Not Returned Yet";
                echo "<tr>
                        <td>" . $row['title'] . "</td>
                        <td>" . $row['issue_date'] . "</td>
                        <td>" . $return_date . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No books issued or all books have been returned.</td></tr>";
        }
        ?>
    </table>

    <a href="logout.php">Logout</a>
</body>
</html>

<?php
$conn->close(); // Close database connection
?>
