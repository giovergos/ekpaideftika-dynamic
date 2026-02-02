<?php
$pageTitle = "Χρήστες";
require_once 'config.php';
include 'header.php';

if (!isTutor()) {
    echo "<p>Δεν έχετε δικαίωμα πρόσβασης.</p>";
    include 'footer.php';
    exit;
}

$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($action === 'delete' && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: users.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $loginame = $_POST['loginame'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($action === 'edit' && $id > 0) {
        $stmt = $conn->prepare("UPDATE users SET firstname=?, lastname=?, loginame=?, password=?, role=? WHERE id=?");
        $stmt->bind_param("sssssi", $firstname, $lastname, $loginame, $password, $role, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, loginame, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $firstname, $lastname, $loginame, $password, $role);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: users.php");
    exit;
}
?>
<h1>Διαχείριση χρηστών</h1>
<div class="page-content">
    <?php if ($action === 'add' || $action === 'edit'): ?>
        <?php
        $firstname = $lastname = $loginame = $password = '';
        $role = 'Student';
        if ($action === 'edit' && $id > 0) {
            $stmt = $conn->prepare("SELECT firstname, lastname, loginame, password, role FROM users WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($firstname, $lastname, $loginame, $password, $role);
            $stmt->fetch();
            $stmt->close();
        }
        ?>
        <h2><?php echo $action === 'edit' ? 'Επεξεργασία χρήστη' : 'Προσθήκη νέου χρήστη'; ?></h2>
        <form method="post">
            <label>Όνομα:</label><br>
            <input type="text" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>"><br><br>
            <label>Επώνυμο:</label><br>
            <input type="text" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>"><br><br>
            <label>Email (loginame):</label><br>
            <input type="email" name="loginame" value="<?php echo htmlspecialchars($loginame); ?>"><br><br>
            <label>Password:</label><br>
            <input type="text" name="password" value="<?php echo htmlspecialchars($password); ?>"><br><br>
            <label>Ρόλος:</label><br>
            <select name="role">
                <option value="Tutor" <?php if ($role === 'Tutor') echo 'selected'; ?>>Tutor</option>
                <option value="Student" <?php if ($role === 'Student') echo 'selected'; ?>>Student</option>
            </select><br><br>
            <button type="submit">Αποθήκευση</button>
        </form>
    <?php else: ?>
        <p><a href="users.php?action=add">Προσθήκη νέου χρήστη</a></p>
        <table border="1" cellpadding="5">
            <tr>
                <th>ID</th><th>Όνομα</th><th>Επώνυμο</th><th>Email</th><th>Ρόλος</th><th>Ενέργειες</th>
            </tr>
            <?php
            $result = $conn->query("SELECT id, firstname, lastname, loginame, role FROM users ORDER BY id ASC");
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                    <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($row['loginame']); ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <a href="users.php?action=edit&id=<?php echo $row['id']; ?>">Επεξεργασία</a> |
                        <a href="users.php?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Διαγραφή;');">Διαγραφή</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
