<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once ROOT.'/utils/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $assigned_to = $_POST['assigned_to'];

   $check_query = "SELECT * FROM assignments WHERE ticket_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $ticket_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $update_query = "UPDATE assignments SET assigned_to = ? WHERE ticket_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ii", $assigned_to, $ticket_id);
        $update_stmt->execute();
    } else {
        $insert_query = "INSERT INTO assignments (ticket_id, assigned_to) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("ii", $ticket_id, $assigned_to);
        $insert_stmt->execute();
    }

    $update_ticket_query = "UPDATE tickets SET assignee_id = ? WHERE id = ?";
    $update_ticket_stmt = $conn->prepare($update_ticket_query);
    $update_ticket_stmt->bind_param("ii", $assigned_to, $ticket_id);
    
    if ($update_ticket_stmt->execute()) {
        echo "<script>alert('User assigned successfully!'); window.location.href='view';</script>";
    } else {
        echo "<script>alert('Error updating ticket assignee.'); window.history.back();</script>";
    }
}
?>
