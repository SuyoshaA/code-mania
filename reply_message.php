<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message_id = $_POST['message_id'];
    $reply = $_POST['reply'];
    $teacher_id = $_SESSION['user_id'];
    
    // Verify the teacher owns this message
    $query = "UPDATE messages SET reply = ?, updated_at = NOW() 
              WHERE id = ? AND teacher_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $reply, $message_id, $teacher_id);
    $stmt->execute();
    $stmt->close();
    
    $_SESSION['success'] = "Reply sent successfully!";
    header("Location: teacher_dashboard.php#student-messages");
    exit();
}
?>