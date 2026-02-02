<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginame = $_POST['loginame'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, firstname, lastname, role, password FROM users WHERE loginame = ?");
    $stmt->bind_param("s", $loginame);
    $stmt->execute();
    $stmt->bind_result($id, $firstname, $lastname, $role, $dbpass);

    if ($stmt->fetch() && $password === $dbpass) { // απλό password όπως ζήτησες
        $_SESSION['user_id'] = $id;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;
        $_SESSION['role'] = $role;
        header("Location: index.php");
        exit;
    } else {
        $error = "Λάθος στοιχεία σύνδεσης.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Σύνδεση</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
    <h1>Σύνδεση</h1>
    <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
    <form method="post">
        <label>Email (loginame):</label><br>
        <input type="text" name="loginame"><br><br>
        <label>Password:</label><br>
        <input type="password" name="password"><br><br>
        <button type="submit">Είσοδος</button>
    </form>
</div>
</body>
</html>
