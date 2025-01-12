<?php
session_start();
include('../cfg.php'); // Plik konfiguracji

// Panel Administracyjny - Zarządzanie Sklepem
function PobierzKategorie() {
    global $db;
    return $db->query("SELECT * FROM categories ORDER BY id ASC");
}

// formularze do edytuj i usun kategorie
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'edit_category') {
        // Edycja kategorii
        $id = $_POST['id'];
        $nazwa = $_POST['nazwa'];
        $matka = $_POST['matka'] ?? null;
        EdytujKategorie($id, $nazwa, $matka);
        echo "<p>Kategoria o ID $id została zaktualizowana.</p>";
    } elseif ($_POST['action'] === 'delete_category') {
        // Usuwanie kategorii
        $id = $_POST['id'];
        UsunKategorie($id);
        echo "<p>Kategoria o ID $id została usunięta.</p>";
    }
}

//formularz dodawania kategorii
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add_category') {
        // Dodawanie kategorii
        $nazwa = $_POST['nazwa'];
        $matka = $_POST['matka'] ?? null;

        if ($matka === "") {
            $matka = null; // Przypisz NULL, jeśli brak nadrzędnej kategorii
        }

        DodajKategorie($nazwa, $matka);
        echo "<p>Kategoria '$nazwa' została dodana.</p>";
    }
}
// Dodawanie kategorii
function DodajKategorie($nazwa, $matka = 0) {
    global $db; // Użycie zmiennej globalnej $db
    $stmt = $db->prepare("INSERT INTO categories (nazwa, matka) VALUES (?, ?)");
    $stmt->bind_param("si", $nazwa, $matka);
    if (!$stmt->execute()) {
        die("Błąd SQL: " . $stmt->error);
    }
    $stmt->close();
}
// edytuj kategorie
function EdytujKategorie($id, $nazwa, $matka) {
    global $db;
    $stmt = $db->prepare("UPDATE categories SET nazwa = ?, matka = ? WHERE id = ?");
    $stmt->bind_param("sii", $nazwa, $matka, $id);
    if (!$stmt->execute()) {
        die("Błąd SQL w EdytujKategorie: " . $stmt->error);
    }
    $stmt->close();
}
// usun kategorie
function UsunKategorie($id) {
    global $db;
    $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        die("Błąd SQL w UsunKategorie: " . $stmt->error);
    }
    $stmt->close();
}

// pokazanie kategorii
function PokazKategorie($matka = null, $poziom = 0) {
    global $db;
    // Dodajemy debugowanie
    echo "<!-- Debug: Próba pobrania kategorii dla matka=" . var_export($matka, true) . " -->\n";
    $sql = "SELECT id, nazwa FROM categories WHERE ";
    $sql .= ($matka === null) ? "matka IS NULL" : "matka = ?";
    $sql .= " ORDER BY nazwa ASC";
    echo "<!-- Debug: SQL=" . htmlspecialchars($sql) . " -->\n";
    $stmt = $db->prepare($sql);
    if ($matka !== null) {
        $stmt->bind_param("i", $matka);
    }
    if (!$stmt->execute()) {
        echo "Błąd SQL: " . $stmt->error;
        return;
    }
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo "<!-- Debug: Brak wyników dla matka=" . var_export($matka, true) . " -->\n";
    }
    while ($row = $result->fetch_assoc()) {
        echo str_repeat("&nbsp;&nbsp;", $poziom) . "- " . 
             htmlspecialchars($row['nazwa']) . 
             " (ID: " . $row['id'] . ")<br>\n";
        PokazKategorie($row['id'], $poziom + 1);
    }
    $stmt->close();
}
$categories = PobierzKategorie();
$category_list = $categories->fetch_all(MYSQLI_ASSOC);
function PobierzProdukty() {
    global $db;
    return $db->query("SELECT * FROM products ORDER BY id ASC");
}

// obsluga formularzy do dodawania, edytowania i usuwania produktow
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Dodawanie produktu
        if ($action === 'add_product') {
            $tytul = $_POST['tytul'];
            $opis = $_POST['opis'];
            $cena_netto = $_POST['cena_netto'];
            $vat = $_POST['vat'];
            $ilosc = $_POST['ilosc'];
            $kategoria = $_POST['kategoria'];
            $gabaryt = $_POST['gabaryt'];
            $zdjecie = $_FILES['zdjecie']['name'];
            $status = isset($_POST['status']) ? 1 : 0;
            $data_wygasniecia = $_POST['data_wygasniecia'];

            // Przenieś plik do katalogu docelowego
            if (!empty($_FILES['zdjecie']['tmp_name'])) {
                $upload_dir = '../uploads/';
                $upload_path = $upload_dir . basename($zdjecie);
                if (!move_uploaded_file($_FILES['zdjecie']['tmp_name'], $upload_path)) {
                    die("Błąd podczas przesyłania pliku zdjęcia.");
                }
            }

            DodajProdukt($tytul, $opis, $cena_netto, $vat, $ilosc, $kategoria, $gabaryt, $zdjecie, $status, $data_wygasniecia);
            echo "<p>Produkt '$tytul' został dodany.</p>";
        }

        // Edytowanie produktu
        elseif ($action === 'edit_product') {
            $id = $_POST['id'];
            $tytul = $_POST['tytul'];
            $opis = $_POST['opis'];
            $cena_netto = $_POST['cena_netto'];
            $vat = $_POST['vat'];
            $ilosc = $_POST['ilosc'];
            $kategoria = $_POST['kategoria'];
            $gabaryt = $_POST['gabaryt'];
            $zdjecie = $_FILES['zdjecie']['name'];
            $status = isset($_POST['status']) ? 1 : 0;
            $data_wygasniecia = $_POST['data_wygasniecia'];

            // Jeśli przesłano nowe zdjęcie, przenieś je do katalogu docelowego
            if (!empty($_FILES['zdjecie']['tmp_name'])) {
                $upload_dir = '../uploads/';
                $upload_path = $upload_dir . basename($zdjecie);
                if (!move_uploaded_file($_FILES['zdjecie']['tmp_name'], $upload_path)) {
                    die("Błąd podczas przesyłania pliku zdjęcia.");
                }
            } else {
                // Jeśli nie przesłano nowego zdjęcia, zachowaj stare
                $zdjecie = $_POST['current_image'];
            }

            EdytujProdukt($id, $tytul, $opis, $cena_netto, $vat, $ilosc, $kategoria, $gabaryt, $zdjecie, $status, $data_wygasniecia);
            echo "<p>Produkt '$tytul' został zaktualizowany.</p>";
        }

        // Usuwanie produktu
        elseif ($action === 'delete_product') {
            $id = $_POST['id'];
            UsunProdukt($id);
            echo "<p>Produkt o ID '$id' został usunięty.</p>";
        }
    }
}

// Dodawanie produktu
function DodajProdukt($tytul, $opis, $cena_netto, $vat, $ilosc, $kategoria, $gabaryt, $zdjecie, $status, $data_wygasniecia) {
    global $db;
    $stmt = $db->prepare("INSERT INTO products (tytul, opis, cena_netto, vat, ilosc, kategoria, gabaryt, zdjecie, status, data_wygasniecia) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiississ", $tytul, $opis, $cena_netto, $vat, $ilosc, $kategoria, $gabaryt, $zdjecie, $status, $data_wygasniecia);
    if (!$stmt->execute()) {
        die("Błąd SQL: " . $stmt->error);
    }
    $stmt->close();
}

// Edycja produktu
function EdytujProdukt($id, $tytul, $opis, $cena_netto, $vat, $ilosc, $kategoria, $gabaryt, $zdjecie, $status, $data_wygasniecia) {
    global $db;
    $stmt = $db->prepare("UPDATE products SET tytul = ?, opis = ?, cena_netto = ?, vat = ?, ilosc = ?, kategoria = ?, gabaryt = ?, zdjecie = ?, status = ?, data_wygasniecia = ? WHERE id = ?");
    $stmt->bind_param("ssdiississi", $tytul, $opis, $cena_netto, $vat, $ilosc, $kategoria, $gabaryt, $zdjecie, $status, $data_wygasniecia, $id);
    if (!$stmt->execute()) {
        die("Błąd SQL: " . $stmt->error);
    }
    $stmt->close();
}

// Usunięcie produktu
function UsunProdukt($id) {
    global $db;
    $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        die("Błąd SQL: " . $stmt->error);
    }
    $stmt->close();
}

function PokazProdukty() {
    global $db;
    $result = $db->query("SELECT * FROM products");
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<h3>" . htmlspecialchars($row['tytul']) . "</h3>";
        echo "<p>" . htmlspecialchars($row['opis']) . "</p>";
        echo "<p>Cena: " . number_format($row['cena_netto'], 2) . " PLN + " . number_format($row['vat'], 2) . "% VAT</p>";
        echo "<p>Ilość: " . $row['ilosc'] . " | Status: " . $row['status'] . "</p>";
        echo "<p>Kategoria: " . $row['kategoria'] . " | Gabaryt: " . $row['gabaryt'] . "</p>";
        echo "</div>";
    }
}
?>

<!-- STRONA  -->
<!doctype html>
<html>
<head>
    <link rel="stylesheet" href="../css/css1.css">
    <title>Admin</title>
</head>
<body>
    <!-- SKLEP ADMIN  -->
    <h1>Panel Administracyjny - Zarządzanie Sklepem</h1>

    <!-- Formularz dodawania kategorii -->
    <h2>Dodaj Kategorię</h2>
    <form method="post">
        <input type="hidden" name="action" value="add_category">
        <label for="nazwa">Nazwa kategorii:</label>
        <input type="text" id="nazwa" name="nazwa" required>
        <br>
        <label for="matka">Nadrzędna kategoria:</label>
        <select id="matka" name="matka">
            <option value="">Brak</option>
            <?php foreach ($category_list as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nazwa']) ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <button type="submit">Dodaj</button>
    </form>

    <!-- Wyświetlanie kategorii -->
    <h2>Drzewo kategorii</h2>
    <form>
    <div class='products'>
    <?php PokazKategorie(); ?> </div></form>

    <!-- Formularz edytowania kategorii -->
    <h2>Edytuj Kategorię</h2>
<form method="POST">
    <input type="hidden" name="action" value="edit_category">
    <label for="id">ID kategorii:</label>
    <select name="id" id="id" required>
        <?php foreach ($category_list as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nazwa']) ?> (ID: <?= $cat['id'] ?>)</option>
        <?php endforeach; ?>
    </select>
    <br>
    <label for="nazwa">Nowa nazwa kategorii:</label>
    <input type="text" id="nazwa" name="nazwa" required>
    <br>
    <label for="matka">Nowa kategoria nadrzędna:</label>
    <select name="matka" id="matka">
        <option value="">Brak</option>
        <?php foreach ($category_list as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nazwa']) ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <button type="submit">Zaktualizuj</button>
</form>


    <!-- Formularz usuwania kategorii-->
    <h2>Usuń Kategorię</h2>
<form method="POST">
    <input type="hidden" name="action" value="delete_category">
    <label for="id">ID kategorii do usunięcia:</label>
    <select name="id" id="id" required>
        <?php foreach ($category_list as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nazwa']) ?> (ID: <?= $cat['id'] ?>)</option>
        <?php endforeach; ?>
    </select>
    <br>
    <button type="submit">Usuń</button>
</form>


    <!-- Formularz dodawania produktow -->
    <h2>Dodaj Produkt</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add_product">
        <label for="tytul">Tytuł produktu:</label>
        <input type="text" id="tytul" name="tytul" required>
        <br>
        <label for="opis">Opis produktu:</label>
        <textarea id="opis" name="opis" required></textarea>
        <br>
        <label for="cena_netto">Cena netto:</label>
        <input type="number" id="cena_netto" name="cena_netto" step="0.01" required>
        <br>
        <label for="vat">VAT (%):</label>
        <input type="number" id="vat" name="vat" step="0.01" required>
        <br>
        <label for="ilosc">Ilość:</label>
        <input type="number" id="ilosc" name="ilosc" required>
        <br>
        <label for="kategoria">Kategoria:</label>
        <select id="kategoria" name="kategoria">
            <?php foreach ($category_list as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nazwa']) ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="gabaryt">Gabaryt:</label>
        <input type="text" id="gabaryt" name="gabaryt" required>
        <br>
        <label for="zdjecie">Zdjęcie produktu:</label>
        <input type="file" id="zdjecie" name="zdjecie" accept="image/*">
        <br>
        <label for="status">Status (1 - aktywny, 0 - nieaktywny):</label>
        <input type="checkbox" id="status" name="status" value="1">
        <br>
        <label for="data_wygasniecia">Data wygaśnięcia:</label>
        <input type="date" id="data_wygasniecia" name="data_wygasniecia">
        <br>
        <button type="submit">Dodaj Produkt</button>
    </form>

    <!-- Formularz usuwania produktow -->
    <h2>Usuń Produkt</h2>
<form method="POST">
    <input type="hidden" name="action" value="delete_product">
    <label for="id">Wybierz produkt do usunięcia:</label>
    <select id="id" name="id" required>
        <?php
        $products = PobierzProdukty();
        while ($product = $products->fetch_assoc()): ?>
            <option value="<?= $product['id'] ?>">
                <?= htmlspecialchars($product['tytul']) ?> (ID: <?= $product['id'] ?>)
            </option>
        <?php endwhile; ?>
    </select>
    <br>
    <p>Czy na pewno chcesz usunąć ten produkt?</p>
    <button type="submit">Usuń</button>
</form>

<!-- Formularz edytowania produktow -->
<h2>Edytuj Produkt</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="edit_product">
    <label for="id">Wybierz produkt:</label>
    <select id="id" name="id" required>
        <?php
        $products = PobierzProdukty();
        while ($product = $products->fetch_assoc()): ?>
            <option value="<?= $product['id'] ?>">
                <?= htmlspecialchars($product['tytul']) ?> (ID: <?= $product['id'] ?>)
            </option>
        <?php endwhile; ?>
    </select>
    <br>
    <label for="tytul">Tytuł produktu:</label>
    <input type="text" id="tytul" name="tytul" required>
    <br>
    <label for="opis">Opis produktu:</label>
    <textarea id="opis" name="opis" required></textarea>
    <br>
    <label for="cena_netto">Cena netto:</label>
    <input type="number" id="cena_netto" name="cena_netto" step="0.01" required>
    <br>
    <label for="vat">VAT (%):</label>
    <input type="number" id="vat" name="vat" step="0.01" required>
    <br>
    <label for="ilosc">Ilość:</label>
    <input type="number" id="ilosc" name="ilosc" required>
    <br>
    <label for="kategoria">Kategoria:</label>
    <select id="kategoria" name="kategoria" required>
        <?php foreach ($category_list as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nazwa']) ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <label for="gabaryt">Gabaryt:</label>
    <input type="text" id="gabaryt" name="gabaryt" required>
    <br>
    <label for="zdjecie">Zdjęcie produktu (opcjonalne):</label>
    <input type="file" id="zdjecie" name="zdjecie" accept="image/*">
    <input type="hidden" name="current_image" value="">
    <br>
    <label for="status">Status (1 - aktywny, 0 - nieaktywny):</label>
    <input type="checkbox" id="status" name="status" value="1">
    <br>
    <label for="data_wygasniecia">Data wygaśnięcia:</label>
    <input type="date" id="data_wygasniecia" name="data_wygasniecia">
    <br>
    <button type="submit">Zapisz zmiany</button>
</form>

    <h2>Produkty</h2>
    <div class="products">
        <?php PokazProdukty(); ?>
    </div>
</body>
</html>
