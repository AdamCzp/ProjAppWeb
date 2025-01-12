<?php
// admin_strona.php
include('../cfg.php');
session_start(); // Dodajemy session_start() na początku, by obsługiwać sesję

// Funkcja formularza logowania
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

// Zmienna do testowania logowania
$login = "root";
$pass = "root";

// Sprawdzanie, czy formularz został wysłany
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    if ($_POST['login'] === $login && $_POST['pass'] === $pass) {
        $_SESSION['loggedin'] = true;
        header('Location: admin.php'); // Przekierowanie po poprawnym logowaniu
        exit; // Konieczne, aby nie wyświetlać formularza po przekierowaniu
    } else {
        echo '<p>Nieprawidłowy login lub hasło.</p>';
        FormularzLogowania(); // Wyświetl formularz ponownie
    }
}

// Sprawdzanie, czy użytkownik jest zalogowany
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    FormularzLogowania(); // Wyświetl formularz logowania, jeśli użytkownik nie jest zalogowany
    exit; // Zatrzymaj dalsze przetwarzanie
}

// Połączenie z bazą danych
if (!$db) {
    die("Połączenie z bazą danych nie działa.");
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/css1.css">
    <title>Logowanie do Panelu Administracyjnego</title>
</head>
<body>
<h2>Logowanie</h2>
    <form method="post" action="">
        <label for="login">Login:</label>
        <input type="text" name="login" id="login" required>
        <br>
        <label for="pass">Hasło:</label>
        <input type="password" name="pass" id="pass" required>
        <br>
        <button type="submit" name="submit">Zaloguj</button>
    </form>
</body>
</html>
