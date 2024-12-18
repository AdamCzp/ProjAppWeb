<?php
/**
 * Projekt: Filmy Oscarowe
 * Wersja: v1.8
 * Autor: Adam Czaplicki (AC 229)
 * Grupa: 1
 */

// Konfiguracja błędów – wyłączenie notatek i ostrzeżeń w środowisku produkcyjnym
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// Ładowanie konfiguracji i modułów
include('../cfg.php'); // Plik konfiguracji
if (!$db) {
    die("Połączenie z bazą danych nie działa.");
} else {
    echo "Połączenie z bazą danych działa poprawnie!";
}
include('../showpage.php'); // Obsługa strony
include('../admin.php'); // Panel administracyjny
include('../contact.php'); // Obsługa kontaktów

// Funkcje zarządzania kategoriami

// Dodawanie kategorii
function DodajKategorie($nazwa, $matka = 0) {
    global $db; // Użycie zmiennej globalnej $db
    $stmt = $db->prepare("INSERT INTO categories (nazwa, matka) VALUES (?, ?)");
    $stmt->bind_param("si", $nazwa, $matka);
    $stmt->execute();
    $stmt->close();
}

// Usuwanie kategorii
function UsunKategorie($id) {
    global $db;
    $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Edytowanie kategorii
function EdytujKategorie($id, $nazwa, $matka = 0) {
    global $db;
    $stmt = $db->prepare("UPDATE categories SET nazwa = ?, matka = ? WHERE id = ?");
    $stmt->bind_param("sii", $nazwa, $matka, $id);
    $stmt->execute();
    $stmt->close();
}

// Wyświetlanie drzewa kategorii
function PokazKategorie($matka = 0, $poziom = 0) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM categories WHERE matka = ? ORDER BY nazwa ASC LIMIT 100");
    $stmt->bind_param("i", $matka);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo str_repeat("--", $poziom) . htmlspecialchars($row['nazwa']) . "<br>";
        PokazKategorie($row['id'], $poziom + 1);
    }

    $stmt->close();
}

// Obsługa formularzy
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        DodajKategorie($_POST['nazwa'], $_POST['matka']);
        echo "<p>Kategoria została dodana.</p>";
    } elseif (isset($_POST['edit'])) {
        EdytujKategorie($_POST['id'], $_POST['nazwa'], $_POST['matka']);
        echo "<p>Kategoria została zaktualizowana.</p>";
    } elseif (isset($_POST['delete'])) {
        UsunKategorie($_POST['id']);
        echo "<p>Kategoria została usunięta.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <!-- Nagłówki i meta dane -->
    <meta charset="UTF-8">
    <meta http-equiv="content-language" content="pl"/>
    <meta name="author" content="AC 229">
    <title>Filmy Oscarowe</title>

    <!-- Skrypty zewnętrzne -->
    <script src="../js/timedate.js" type="text/javascript"></script>
    <script src="../js/kolorujto.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Stylizacja -->
    <link rel="stylesheet" href="../css/css.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body onload="startClock()">
    <!-- Dynamiczny zegarek i data -->
    <div id="zegarek"></div>
    <div id="data"></div>

    <div class="container">
        <!-- Nagłówek -->
        <header class="header">
            <a href="index.php?id=filmy"><h1>Filmy Oscarowe ostatnich lat</h1></a>
        </header>

        <!-- Nawigacja -->
        <nav class="nava">
            <ul class="Tytularna">
                <?php
                // Lista filmów z danymi dynamicznymi
                $movies = [
                    ['title' => 'Oppenheimer', 'image' => '../img/z1.jpg'],
                    ['title' => 'Wszystko wszędzie naraz', 'image' => '../img/z2.jpg'],
                    ['title' => 'Coda', 'image' => '../img/z3.jpg'],
                    ['title' => 'Nomadland', 'image' => '../img/z4.jpg'],
                    ['title' => 'Parasite', 'image' => '../img/z5.jpg']
                ];

                foreach ($movies as $movie) {
                    echo "<li class='tytuly'>
                            <div class='tytultekst'>{$movie['title']}</div>
                            <a href='index.php?id={$movie['title']}'>
                                <img src='{$movie['image']}' alt='{$movie['title']}'>
                            </a>
                          </li>";
                }
                ?>
            </ul>
        </nav>
    </div>

    <!-- Główna treść strony -->
    <div class="content">
        <?php
        // Bezpieczne pobieranie parametrów GET
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Ładowanie treści w zależności od ID
        if ($id === 'Oppenheimer') {
            include 'Oppenheime.html';
        } elseif ($id === 'Wszystko_wszędzie_naraz') {
            include 'Wszystko_wszędzie_naraz.html';
        } elseif ($id === 'Coda') {
            include 'Coda.html';
        } elseif ($id === 'Nomadland') {
            include 'Nomadland.html';
        } elseif ($id === 'Parasite') {
            include 'Parasite.html';
        } elseif ($id === 'filmy') {
            include 'filmy.html';
        } else {
            echo "<h2>Witamy na stronie Filmy Oscarowe</h2><p>Wybierz film, aby zobaczyć szczegóły.</p>";
        }
        ?>
    </div>

    <!-- Sekcja kontaktowa -->
    <div class="kontakt">
        <a href="index.php?id=Kontakt"><h3>Kontakt</h3></a>
    </div>

    <?php
    if ($id === 'Kontakt') {
        include 'Kontakt.html';
    }
    ?>

    <h1>Zarządzanie kategoriami</h1>

    <!-- Formularz dodawania kategorii -->
    <h2>Dodaj kategorię</h2>
    <form method="POST">
        <label>Nazwa: <input type="text" name="nazwa" required></label>
        <label>Matka (ID): <input type="number" name="matka" value="0"></label>
        <button type="submit" name="add">Dodaj</button>
    </form>

    <!-- Wyświetlanie kategorii -->
    <h2>Drzewo kategorii</h2>
    <?php PokazKategorie(); ?>

    <!-- Formularz edytowania/usuwania kategorii -->
    <h2>Edytuj lub usuń kategorię</h2>
    <form method="POST">
        <label>ID: <input type="number" name="id" required></label>
        <label>Nowa nazwa: <input type="text" name="nazwa"></label>
        <label>Nowa matka (ID): <input type="number" name="matka" value="0"></label>
        <button type="submit" name="edit">Edytuj</button>
        <button type="submit" name="delete">Usuń</button>
    </form>

    <!-- Stopka z informacjami -->
    <?php
    $nr_indeksu = '169229';
    $nr_grupy = '1';
    echo 'Adam Czaplicki grupa ' . htmlspecialchars($nr_grupy) . '<br />';
    echo ' nr indeksu ' . htmlspecialchars($nr_indeksu) . '<br />';
    ?>

</body>
</html>

