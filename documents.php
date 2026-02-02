<?php
$pageTitle = "Έγγραφα μαθήματος";
require_once 'config.php';
include 'header.php';

$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isTutor()) {
    if ($action === 'delete' && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM documents WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: documents.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $filename = $_POST['filename'];

        if ($action === 'edit' && $id > 0) {
            $stmt = $conn->prepare("UPDATE documents SET title=?, description=?, filename=? WHERE id=?");
            $stmt->bind_param("sssi", $title, $description, $filename, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO documents (title, description, filename) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $description, $filename);
        }
        $stmt->execute();
        $stmt->close();
        header("Location: documents.php");
        exit;
    }
}
?>
<h1>Έγγραφα μαθήματος</h1>
<div class="page-content">
    <a name="top"></a>

    <?php if (isTutor() && ($action === 'add' || $action === 'edit')): ?>
        <?php
        $title = $description = $filename = '';
        if ($action === 'edit' && $id > 0) {
            $stmt = $conn->prepare("SELECT title, description, filename FROM documents WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($title, $description, $filename);
            $stmt->fetch();
            $stmt->close();
        }
        ?>
        <h2><?php echo $action === 'edit' ? 'Επεξεργασία εγγράφου' : 'Προσθήκη νέου εγγράφου'; ?></h2>
        <form method="post">
            <label>Τίτλος:</label><br>
            <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>"><br><br>
            <label>Περιγραφή:</label><br>
            <textarea name="description" rows="4"><?php echo htmlspecialchars($description); ?></textarea><br><br>
            <label>Όνομα/θέση αρχείου:</label><br>
            <input type="text" name="filename" value="<?php echo htmlspecialchars($filename); ?>"><br><br>
            <button type="submit">Αποθήκευση</button>
        </form>
        <hr>
    <?php elseif (isTutor()): ?>
        <p><a href="documents.php?action=add">Προσθήκη νέου εγγράφου</a></p>
    <?php endif; ?>

    <?php
    $result = $conn->query("SELECT id, title, description, filename FROM documents ORDER BY id DESC");
    while ($row = $result->fetch_assoc()):
    ?>
        <h2><?php echo htmlspecialchars($row['title']); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        <p><a href="<?php echo htmlspecialchars($row['filename']); ?>">Κατέβασμα αρχείου</a></p>
        <?php if (isTutor()): ?>
            <p>
                <a href="documents.php?action=edit&id=<?php echo $row['id']; ?>">Επεξεργασία</a> |
                <a href="documents.php?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Διαγραφή;');">Διαγραφή</a>
            </p>
        <?php endif; ?>
        <hr>
    <?php endwhile; ?>

    <p><a href="#top">Επιστροφή στην κορυφή</a></p>
</div>
<?php include 'footer.php'; ?>
