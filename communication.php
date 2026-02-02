<?php
$pageTitle = "Επικοινωνία";
require_once 'config.php';
include 'header.php';

$messageSent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender = $_POST['sender'];
    $subject = $_POST['subject'];
    $text = $_POST['message'];

    $result = $conn->query("SELECT loginame FROM users WHERE role='Tutor'");
    $to = [];
    while ($row = $result->fetch_assoc()) {
        $to[] = $row['loginame'];
    }

    $headers = "From: " . $sender . "\r\n" .
               "Reply-To: " . $sender . "\r\n" .
               "Content-Type: text/plain; charset=UTF-8\r\n";

    foreach ($to as $email) {
        @mail($email, $subject, $text, $headers);
    }

    $messageSent = true;
}
?>
<h1>Επικοινωνία</h1>
<div class="page-content">
    <h2>Αποστολή email μέσω web φόρμας</h2>

    <?php if ($messageSent): ?>
        <p>Το μήνυμα στάλθηκε στους tutors.</p>
    <?php endif; ?>

    <form method="post">
        <label>Αποστολέας (email):</label><br>
        <input type="email" name="sender"><br><br>

        <label>Θέμα:</label><br>
        <input type="text" name="subject"><br><br>

        <label>Κείμενο:</label><br>
        <textarea name="message" rows="5"></textarea><br><br>

        <button type="submit">Αποστολή</button>
    </form>

    <h2>Αποστολή email μέσω προγράμματος</h2>
    <p>Μπορείτε επίσης να στείλετε email στον tutor:</p>
    <p><a href="mailto:tutor@example.com">tutor@example.com</a></p>
</div>
<?php include 'footer.php'; ?>
