<?php require_once 'auth.php'; ?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'HTML Site'; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <div class="sidebar">
        <a href="index.php">Αρχική σελίδα</a>
        <a href="announcements.php">Ανακοινώσεις</a>
        <a href="communication.php">Επικοινωνία</a>
        <a href="documents.php">Έγγραφα μαθήματος</a>
        <a href="homeworks.php">Εργασίες</a>
        <?php if (isTutor()): ?>
            <a href="users.php">Χρήστες</a>
        <?php endif; ?>
        <a href="logout.php">Έξοδος</a>
    </div>

    <div class="content">
    </div>
</div>
</body>
</html>
