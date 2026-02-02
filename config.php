<?php
$DB_HOST = 'localhost';
$DB_NAME = 'html_site';
$DB_USER = 'webuser';      // χρήστης της βάσης, της επιλογής σου
$DB_PASS = 'webpassword';  // κωδικός της βάσης

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
