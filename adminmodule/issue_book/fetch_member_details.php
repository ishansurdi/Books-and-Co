<?php
$mid = $_GET['mid'];
$conn = new mysqli('localhost', 'root', '123456789', 'library');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query
$sql = "
    SELECT 
        m.membership_id, 
        m.first_name, 
        m.last_name, 
        t.account_status
    FROM membership_form m
    LEFT JOIN Transactions t ON m.membership_id = t.MID
    WHERE m.membership_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $mid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $member = $result->fetch_assoc();
    // Return member data as JSON
    echo json_encode($member);
} else {
    // Return error message if no member found
    echo json_encode(array('error' => 'No member found'));
}

$stmt->close();
$conn->close();
?>
