<?php
include('db.php');

$query = "SELECT book_id, title, author, available_copies FROM books";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['title'] . "</td>
                <td>" . $row['author'] . "</td>
                <td>" . $row['available_copies'] . "</td>
              </tr>";
    }
} else {
    echo "No books available.";
}

$conn->close();
?>
