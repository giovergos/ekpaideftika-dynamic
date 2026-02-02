<?php
$pageTitle = "Εργασίες";
require_once 'config.php';
include 'header.php';

$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isTutor()) {
    if ($action === 'delete' && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM homeworks WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: homeworks.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $goals = $_POST['goals'];
        $filename = $_POST['filename'];
        $deliverables = $_POST['deliverables'];
        $deadline = $_POST['deadline'];

        if ($action === 'edit' && $id > 0) {
            $stmt = $conn->prepare("UPDATE homeworks SET goals=?, filename=?, deliverables=?, deadline=? WHERE id=?");
            $stmt->bind_param("ssssi", $goals, $filename, $deliverables, $deadline, $id);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $conn->prepare("INSERT INTO homeworks (goals, filename, deliverables, deadline) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $goals, $filename, $deliverables, $deadline);
            $stmt->execute();
            $newId = $stmt->insert_id;
            $stmt->close();

            // αυτόματη ανακοίνωση
            $date = date('Y-m-d');
            $title = "Υποβλήθηκε η εργασία " . $newId;
            $body = "Η ημερομηνία παράδοσης της εργασίας είναι " . $deadline . ".";
            $stmt = $conn->prepare("INSERT INTO announcements (date, title, body) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $date, $title, $body);
            $stmt->execute();
            $stmt->close();
        }

        header("Location: homeworks.php");
        exit;
    }
}
?>
<h1>Εργασίες</h1>
<div class="page-content">
    <a name="top"></a>

    <?php if (isTutor() && ($action === 'add' || $action === 'edit')): ?>
        <?php
        $goals = $filename = $deliverables = '';
        $deadline = date('Y-m-d');
        if ($action === 'edit' && $id > 0) {
            $stmt = $conn->prepare("SELECT goals, filename, deliverables, deadline FROM homeworks WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($goals, $filename, $deliverables, $deadline);
            $stmt->fetch();
            $stmt->close();
        }
        ?>
        <h2><?php echo $action === 'edit' ? 'Επεξεργασία εργασίας' : 'Προσθήκη νέας εργασίας'; ?></h2>
        <form method="post">
            <label>Στόχοι:</label><br>
            <textarea name="goals" rows="3"><?php echo htmlspecialchars($goals); ?></textarea><br><br>
            <label>Εκφώνηση (όνομα/θέση αρχείου):</label><br>
            <input type="text" name="filename" value="<?php echo htmlspecialchars($filename); ?>"><br><br>
            <label>Παραδοτέα:</label><br>
            <textarea name="deliverables" rows="3"><?php echo htmlspecialchars($deliverables); ?></textarea><br><br>
            <label>Ημερομηνία παράδοσης:</label><br>
            <input type="date" name="deadline" value="<?php echo $deadline; ?>"><br><br>
            <button type="submit">Αποθήκευση</button>
        </form>
        <hr>
    <?php elseif (isTutor()): ?>
        <p><a href="homeworks.php?action=add">Προσθήκη νέας εργασίας</a></p>
    <?php endif; ?>

    <?php
    $result = $conn->query("SELECT id, goals, filename, deliverables, deadline FROM homeworks ORDER BY deadline ASC");
    while ($row = $result->fetch_assoc()):
    ?>
        <h2>Εργασία <?php echo $row['id']; ?></h2>
        <p><strong>Στόχοι:</strong> <?php echo nl2br(htmlspecialchars($row['goals'])); ?></p>
        <p><strong>Εκφώνηση:</strong> <a href="<?php echo htmlspecialchars($row['filename']); ?>">Κατέβασμα εκφώνησης</a></p>
        <p><strong>Παραδοτέα:</strong> <?php echo nl2br(htmlspecialchars($row['deliverables'])); ?></p>
        <p><strong>Καταληκτική ημερομηνία:</strong> <?php echo $row['deadline']; ?></p>
        <?php if (isTutor()): ?>
            <p>
                <a href="homeworks.php?action=edit&id=<?php echo $row['id']; ?>">Επεξεργασία</a> |
                <a href="homeworks.php?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Διαγραφή;');">Διαγραφή</a>
            </p>
        <?php endif; ?>
        <hr>
    <?php endwhile; ?>

    <p><a href="#top">Επιστροφή στην κορυφή</a></p>
</div>
<?php include 'footer.php'; ?>
