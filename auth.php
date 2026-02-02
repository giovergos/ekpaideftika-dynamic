<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

function isTutor() {
    return (isset($_SESSION['role']) && $_SESSION['role'] === 'Tutor');
}
?>
