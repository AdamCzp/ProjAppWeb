<?php
session_start();
$pass = "pass";
$login = "login";
<link rel="stylesheet" href="../css/css.css">
function FormularzLogowania() {
    echo '
        <form method="post" action="">
            <label for="login">Login:</label>
            <input type="text" name="login" id="login" required>
            <br>
            <label for="pass">Hasło:</label>
            <input type="password" name="pass" id="pass" required>
            <br>
            <button type="submit" name="submit">Zaloguj</button>
        </form>
    ';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    include('cfg.php');
    if ($_POST['login'] === $login && $_POST['pass'] === $pass) {
        $_SESSION['loggedin'] = true;
        header('Location: admin.php');
    } else {
        echo '<p>Nieprawidłowy login lub hasło.</p>';
        FormularzLogowania();
    }
}

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    FormularzLogowania();
    exit;
}

function ListaPodstron() {
    include('cfg.php');
    $query = "SELECT id, page_title FROM page_list";
    $result = mysqli_query($GLOBALS['link'], $query);

    echo '<table>';
    echo '<tr><th>ID</th><th>Tytuł</th><th>Opcje</th></tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['page_title']) . '</td>';
        echo '<td>
            <a href="edit.php?id=' . $row['id'] . '">Edytuj</a>
            <a href="delete.php?id=' . $row['id'] . '">Usuń</a>
            </td>';
        echo '</tr>';
    }
    echo '</table>';
}

function EdytujPodstrone($id) {
    include('cfg.php');
    $query = "SELECT * FROM page_list WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($GLOBALS['link'], $query);
    $row = mysqli_fetch_assoc($result);

    echo '
        <form method="post" action="">
            <label for="title">Tytuł:</label>
            <input type="text" name="title" id="title" value="' . htmlspecialchars($row['page_title']) . '" required>
            <br>
            <label for="content">Treść:</label>
            <textarea name="content" id="content">' . htmlspecialchars($row['page_content']) . '</textarea>
            <br>
            <label for="status">Aktywna:</label>
            <input type="checkbox" name="status" id="status" ' . ($row['status'] == 1 ? 'checked' : '') . '>
            <br>
            <button type="submit" name="submit">Zapisz zmiany</button>
        </form>
    ';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
        $title = htmlspecialchars($_POST['title']);
        $content = htmlspecialchars($_POST['content']);
        $status = isset($_POST['status']) ? 1 : 0;

        $updateQuery = "UPDATE page_list SET page_title = '$title', page_content = '$content', status = '$status' WHERE id = '$id' LIMIT 1";
        mysqli_query($GLOBALS['link'], $updateQuery);
        echo '<p>Zmiany zapisane.</p>';
    }
}

function DodajNowaPodstrone() {
    echo '
        <form method="post" action="">
            <label for="title">Tytuł:</label>
            <input type="text" name="title" id="title" required>
            <br>
            <label for="content">Treść:</label>
            <textarea name="content" id="content"></textarea>
            <br>
            <label for="status">Aktywna:</label>
            <input type="checkbox" name="status" id="status">
            <br>
            <button type="submit" name="submit">Dodaj podstronę</button>
        </form>
    ';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
        include('cfg.php');
        $title = htmlspecialchars($_POST['title']);
        $content = htmlspecialchars($_POST['content']);
        $status = isset($_POST['status']) ? 1 : 0;

        $insertQuery = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$title', '$content', '$status')";
        mysqli_query($GLOBALS['link'], $insertQuery);
        echo '<p>Podstrona została dodana.</p>';
    }
}

function UsunPodstrone($id) {
    include('cfg.php');
    $query = "DELETE FROM page_list WHERE id = '$id' LIMIT 1";
    mysqli_query($GLOBALS['link'], $query);
    echo '<p>Podstrona została usunięta.</p>';
}



?>

<!doctype html>
<html>
     <head>
     <link rel="stylesheet" href="../css/css.css">
     </head>
     <body>

     </body>
</html>
