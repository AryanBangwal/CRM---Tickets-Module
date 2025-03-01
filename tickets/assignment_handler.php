<?php
session_start();
require_once '../utils/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $assigned_to = $_POST['assigned_to'];

    // Check if assignment already exists
    $check_query = "SELECT * FROM assignments WHERE ticket_id = $ticket_id AND assigned_to = $assigned_to";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) == 0) 
    {
        $query = "INSERT INTO assignments (ticket_id, assigned_to) VALUES ($ticket_id, $assigned_to)";
        if (mysqli_query($conn, $query)) 
        {
            echo "<script>alert('User assigned successfully!'); window.location.href='view.php';</script>";
        } 
        else 
        {
            echo "<script>alert('Error assigning user.'); window.history.back();</script>";
        }
    } 
    else 
    {
        echo "<script>alert('User is already assigned to this ticket.'); window.history.back();</script>";
    }
}
?>
