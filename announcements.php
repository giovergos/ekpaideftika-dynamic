<?php
$pageTitle = "Ανακοινώσεις";
require_once 'config.php';
include 'header.php';

$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isTutor()) {
    if ($action === 'delete' && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: announcements.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $date = $_POST['date'];
        $title = $_POST['title'];
        $body = $_POST['body'];

        if ($action === 'edit' && $id > 0) {
            $stmt = $conn->prepare("UPDATE announcements SET date=?, title=?, body=? WHERE id=?");
            $stmt->bind_param("sssi", $date, $title, $body, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO announcements (date, title, body) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $date, $title, $body);
        }
        $stmt->execute();
        $stmt->close();
        header("Location: announcements.php");
        exit;
    }
}
?>
<h1>Ανακοινώσεις</h1>
<div class="page-content">
    <a name="top"></a>

    <?php if (isTutor() && ($action === 'add' || $action === 'edit')): ?>
        <?php
        $date = date('Y-m-d');
        $title = '';
        $body = '';
        if ($action === 'edit' && $id > 0) {
            $stmt = $conn->prepare("SELECT date, title, body FROM announcements WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($date, $title, $body);
            $stmt->fetch();
            $stmt->close();
        }
        ?>
        <h2><?php echo $action === 'edit' ? 'Επεξεργασία ανακοίνωσης' : 'Προσθήκη νέας ανακοίνωσης'; ?></h2>
        <form method="post">
            <label>Ημερομηνία:</label><br>
            <input type="date" name="date" value="<?php echo $date; ?>"><br><br>
            <label>Θέμα:</label><br>
            <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>"><br><br>
            <label>Κυρίως κείμενο:</label><br>
            <textarea name="body" rows="5"><?php echo htmlspecialchars($body); ?></textarea><br><br>
            <button type="submit">Αποθήκευση</button>
        </form>
        <hr>
    <?php elseif (isTutor()): ?>
        <p><a href="announcements.php?action=add">Προσθήκη νέας ανακοίνωσης</a></p>
    <?php endif; ?>

    <?php
    $result = $conn->query("SELECT id, date, title, body FROM announcements ORDER BY date DESC, id DESC");
    while ($row = $result->fetch_assoc()):
    ?>
        <h2><?php echo $row['date'] . " – " . htmlspecialchars($row['title']); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($row['body'])); ?></p>
        <?php if (isTutor()): ?>
            <p>
                <a href="announcements.php?action=edit&id=<?php echo $row['id']; ?>">Επεξεργασία</a> |
                <a href="announcements.php?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Διαγραφή;');">Διαγραφή</a>
            </p>
        <?php endif; ?>
        <hr>
    <?php endwhile; ?>

    <p><a href="#top">Επιστροφή στην κορυφή</a></p>
</div>
<?php include 'footer.php'; ?>
