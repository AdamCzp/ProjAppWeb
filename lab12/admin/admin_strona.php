<?php
session_start();
include('../cfg.php'); // Plik konfiguracji

// Panel Administracyjny - Zarządzanie Podstronami

// Funkcja wyświetlająca listę podstron
function ListaPodstron() {
    global $db;
    $result = $db->query("SELECT * FROM page_list");
    return $result;
}

// Funkcja dodawania nowej podstrony
function DodajNowaPodstrone($tytul, $tresc, $status) {
    global $db;
    $stmt = $db->prepare("INSERT INTO page_list (page_title, page_content, status) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $tytul, $tresc, $status);
    $stmt->execute();
    $stmt->close();
}

// Funkcja edycji podstrony
function EdytujPodstrone($id, $tytul, $tresc, $status) {
    global $db;
    $stmt = $db->prepare("UPDATE page_list SET page_title = ?, page_content = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssii", $tytul, $tresc, $status, $id);
    $stmt->execute();
    $stmt->close();
}

// Funkcja usuwania podstrony
function UsunPodstrone($id) {
    global $db;
    $stmt = $db->prepare("DELETE FROM page_list WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Obsługa formularzy dotyczących podstron
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'add') {
            $title = htmlspecialchars($_POST['add_page_title']);
            $content = htmlspecialchars($_POST['add_page_content']);
            $status = isset($_POST['add_status']) ? 1 : 0;
            DodajNowaPodstrone($title, $content, $status);
            echo "<p>Podstrona została dodana.</p>";
        } elseif ($action === 'edit') {
            $id = intval($_POST['edit_page_id']);
            $title = htmlspecialchars($_POST['edit_page_title']);
            $content = htmlspecialchars($_POST['edit_page_content']);
            $status = isset($_POST['edit_status']) ? 1 : 0;
            EdytujPodstrone($id, $title, $content, $status);
            echo "<p>Podstrona została zaktualizowana.</p>";
        } elseif ($action === 'delete') {
            $id = intval($_POST['delete_page_id']);
            UsunPodstrone($id);
            echo "<p>Podstrona została usunięta.</p>";
        }
    }
}

$page_list = ListaPodstron();
?>

<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="../css/css1.css">
    <title>AdminStrony</title>
</head>
<body>
<h1>Panel Administracyjny - Zarządzanie Podstronami</h1>

<!-- Formularz dodawania podstrony -->
<h2>Dodaj podstronę</h2>
<form method="post">
    <input type="hidden" name="action" value="add">
    <label for="add_page_title">Tytuł podstrony:</label>
    <input type="text" id="add_page_title" name="add_page_title" required>
    <br>
    <label for="add_page_content">Treść podstrony:</label>
    <textarea id="add_page_content" name="add_page_content" required></textarea>
    <br>
    <label for="add_status">Aktywna:</label>
    <input type="checkbox" id="add_status" name="add_status">
    <br>
    <button type="submit">Dodaj</button>
</form>

<!-- Formularz edycji podstrony -->
<h2>Edytuj podstronę</h2>
<form method="post">
    <input type="hidden" name="action" value="edit">
    <label for="edit_page_id">Wybierz podstronę:</label>
    <select id="edit_page_id" name="edit_page_id" required>
        <?php while ($page = $page_list->fetch_assoc()): ?>
            <option value="<?= $page['id'] ?>"><?= htmlspecialchars($page['page_title']) ?></option>
        <?php endwhile; ?>
    </select>
    <br>
    <label for="edit_page_title">Tytuł podstrony:</label>
    <input type="text" id="edit_page_title" name="edit_page_title" required>
    <br>
    <label for="edit_page_content">Treść podstrony:</label>
    <textarea id="edit_page_content" name="edit_page_content" required></textarea>
    <br>
    <label for="edit_status">Aktywna:</label>
    <input type="checkbox" id="edit_status" name="edit_status">
    <br>
    <button type="submit">Zapisz zmiany</button>
</form>

<!-- Formularz usuwania podstrony -->
<h2>Usuń podstronę</h2>
<form method="post">
    <input type="hidden" name="action" value="delete">
    <label for="delete_page_id">Wybierz podstronę:</label>
    <select id="delete_page_id" name="delete_page_id" required>
        <?php $page_list->data_seek(0); while ($page = $page_list->fetch_assoc()): ?>
            <option value="<?= $page['id'] ?>"><?= htmlspecialchars($page['page_title']) ?></option>
        <?php endwhile; ?>
    </select>
    <br>
    <button type="submit">Usuń</button>
</form>

<!-- Wyświetlanie podstron -->
<h2>Lista Podstron</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Tytuł</th>
        <th>Treść</th>
        <th>Status</th>
    </tr>
    <?php $page_list->data_seek(0); while ($page = $page_list->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($page['id']) ?></td>
            <td><?= htmlspecialchars($page['page_title']) ?></td>
            <td><?= htmlspecialchars($page['page_content']) ?></td>
            <td><?= $page['status'] == 1 ? 'Aktywna' : 'Nieaktywna' ?></td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>