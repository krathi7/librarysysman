<?php
include('db.php');
session_start(); // Start the session to get the logged-in user's information

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: login.php'); // Redirect to login if the user is not logged in
    exit();
}

// Fetch the logged-in student's ID from the session
$student_id = $_SESSION['student_id'];

// Prepare the SQL query to fetch the student's reading history
$query = "SELECT t.transaction_id, b.title, t.issue_date, t.return_date
          FROM transactions t
          JOIN books b ON t.book_id = b.book_id
          WHERE t.student_id = ?";

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $student_id); // 'i' means the parameter is an integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['title'] . "</td>
                <td>" . $row['issue_date'] . "</td>
                <td>" . ($row['return_date'] ? $row['return_date'] : 'Not Returned Yet') . "</td>
              </tr>";
    }
} else {
    echo "No reading history found.";
}

$stmt->close();
$conn->close();
?>
