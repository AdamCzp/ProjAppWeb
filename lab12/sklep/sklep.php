<?php
session_start();
include('../cfg.php');

// Obsługa formularzy
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dodanie produktu do koszyka
    if (isset($_POST['add_to_cart'])) {
        addToCart(
            $_POST['product_id'],
            $_POST['product_name'],
            (float)$_POST['price_netto'],
            (float)$_POST['vat'],
            (int)$_POST['quantity']
        );
    }
    // Usunięcie produktu z koszyka
    elseif (isset($_POST['remove_product'])) {
        removeFromCart($_POST['product_id']);
    }
    // Aktualizacja ilości produktu
    elseif (isset($_POST['update_quantity'])) {
        updateCartQuantity(
            $_POST['product_id'],
            (int)$_POST['new_quantity']
        );
    }
}

// Wyświetlanie produktów
function PokazProdukty() {
    global $db;
    $result = $db->query("SELECT * FROM products");
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<h3>" . htmlspecialchars($row['tytul']) . "</h3>";
        echo "<p>" . htmlspecialchars($row['opis']) . "</p>";
        echo "<p>Cena: " . number_format($row['cena_netto'], 2) . " PLN + " . number_format($row['vat'], 2) . "% VAT</p>";
        echo "<p>Ilość: " . $row['ilosc'] . " | Status: " . $row['status'] . "</p>";
        echo "<form method='POST'>
                <input type='hidden' name='product_id' value='" . $row['id'] . "'>
                <input type='hidden' name='product_name' value='" . htmlspecialchars($row['tytul']) . "'>
                <input type='hidden' name='price_netto' value='" . $row['cena_netto'] . "'>
                <input type='hidden' name='vat' value='" . $row['vat'] . "'>
                <input type='number' name='quantity' value='1' min='1' max='" . $row['ilosc'] . "' style='width: 60px;'>
                <button type='submit' name='add_to_cart'>Dodaj do koszyka</button>
              </form>";
        echo "</div>";
    }
}

// Dodanie produktu do koszyka
function addToCart($productId, $productName, $priceNetto, $vat, $quantity) {
    global $db;
    $stmt = $db->prepare("SELECT ilosc FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if (!$product) {
        echo "<p class='error'>Produkt o ID " . htmlspecialchars($productId) . " nie istnieje.</p>";
        return;
    }
    
    if ($quantity > $product['ilosc']) {
        echo "<p class='error'>Nie można dodać więcej niż " . htmlspecialchars($product['ilosc']) . " sztuk.</p>";
        return;
    }
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $priceBrutto = $priceNetto * (1 + $vat / 100);
    $_SESSION['cart'][$productId] = [
        'name' => $productName,
        'priceNetto' => $priceNetto,
        'priceBrutto' => $priceBrutto,
        'vat' => $vat,
        'quantity' => (int)$quantity,
    ];
    echo "<p class='success'>Produkt został dodany do koszyka.</p>";
}

// Aktualizacja ilości produktu w koszyku
function updateCartQuantity($productId, $newQuantity) {
    global $db;
    
    if (!isset($_SESSION['cart'][$productId])) {
        echo "<p class='error'>Produkt nie znajduje się w koszyku.</p>";
        return;
    }
    
    // Sprawdź dostępną ilość w bazie
    $stmt = $db->prepare("SELECT ilosc FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if ($newQuantity <= 0) {
        removeFromCart($productId);
        return;
    }
    
    if ($newQuantity > $product['ilosc']) {
        echo "<p class='error'>Nie można dodać więcej niż " . htmlspecialchars($product['ilosc']) . " sztuk.</p>";
        return;
    }
    
    $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    
    // Przekieruj z powrotem na tę samą stronę aby odświeżyć widok
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Usunięcie produktu z koszyka
function removeFromCart($productId) {
    if (!isset($_SESSION['cart'])) {
        return;
    }
    
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        
        // Sprawdź czy koszyk jest pusty po usunięciu produktu
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
        
        // Przekieruj z powrotem na tę samą stronę aby odświeżyć widok
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Wyświetlenie koszyka
function showCart() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "<p>Koszyk jest pusty.</p>";
        return;
    }

    $total = 0;
    echo "<table border='1'>";
    echo "<tr>
            <th>Tytuł</th>
            <th>Cena netto</th>
            <th>VAT</th>
            <th>Cena brutto</th>
            <th>Ilość</th>
            <th>Wartość</th>
            <th>Akcje</th>
          </tr>";
          
    foreach ($_SESSION['cart'] as $productId => $product) {
        $subtotal = $product['priceBrutto'] * $product['quantity'];
        $total += $subtotal;
        echo "<tr>
            <td>" . htmlspecialchars($product['name']) . "</td>
            <td>" . number_format($product['priceNetto'], 2) . " PLN</td>
            <td>" . number_format($product['vat'], 2) . "%</td>
            <td>" . number_format($product['priceBrutto'], 2) . " PLN</td>
            <td>
                <form method='POST' style='display: inline;'>
                    <input type='hidden' name='product_id' value='" . htmlspecialchars($productId) . "'>
                    <input type='number' name='new_quantity' value='" . $product['quantity'] . "' min='0' style='width: 60px;'>
                    <button type='submit' name='update_quantity'>Aktualizuj</button>
                </form>
            </td>
            <td>" . number_format($subtotal, 2) . " PLN</td>
            <td>
                <form method='POST' style='display: inline;'>
                    <input type='hidden' name='product_id' value='" . htmlspecialchars($productId) . "'>
                    <button type='submit' name='remove_product'>Usuń</button>
                </form>
            </td>
        </tr>";
    }
    echo "<tr><td colspan='6'><strong>Łączna wartość</strong></td><td><strong>" . number_format($total, 2) . " PLN</strong></td></tr>";
    echo "</table>";
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-language" content="pl"/>
    <meta name="author" content="AC 229">
    <title>Sklep</title>
    <script src="../js/timedate.js" type="text/javascript"></script>
    <script src="../js/kolorujto.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="../css/css1.css">
</head>
<body onload="startClock()">
    <div id="zegarek"></div>
    <div id="data"></div>

    <div class="container">
        <header class="header">
            <h1>SKLEP</h1>
        </header>
    </div>

    <h2>Produkty</h2>
    <div class="products">
        <?php PokazProdukty(); ?>
    </div>

    <h2>Koszyk</h2>
    <div class="cart">
        <?php showCart(); ?>
    </div>

</body>
</html>