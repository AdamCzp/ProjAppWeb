<?php
// Funkcja wyświetlająca formularz kontaktowy
function PokazKontakt() {
    return '
    <form action="contact.php" method="POST">
        <label for="name">Imię:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="message">Wiadomość:</label><br>
        <textarea id="message" name="message" rows="5" required></textarea><br><br>
        
        <button type="submit" name="submit">Wyślij</button>
    </form>';
}

// Funkcja wysyłająca e-mail kontaktowy
function wyslijMailkontakt($email, $temat, $tresc) {
    if (empty($temat) || empty($tresc) || empty($email)) {
        echo "Nie podano wszystkich wymaganych danych!";
    } else {
        $recipient = "admin@moja-strona.pl"; // Adres, na który wysyłamy wiadomość
        $headers = "From: Formularz kontaktowy <$email>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/".phpversion()."\r\n";
        
        mail($recipient, $temat, $tresc, $headers);
        echo "Wiadomość wysłana!";
    }
}

// Funkcja przypomnienia hasła
function PrzypomnijHaslo($email) {
    $subject = "Przypomnienie hasła";
    // Przykładowe hasło - w rzeczywistości powinno być wygenerowane lub pobrane z bazy danych
    $password = "twojeHaslo123"; 
    
    $message = "Twoje hasło do panelu admina to: " . $password;
    
    // Wywołanie funkcji wysyłającej e-mail (WyslijMailKontakt)
    wyslijMailkontakt($email, $subject, $message);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/css1.css">
    <title>KONTAKT - POMOC</title>
</head>
<body>
    <h1>Formularz Wysyłania E-maila DO NAS</h1>
    <form action="contact.php" method="POST">
        <label for="name">Imię:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="message">Wiadomość:</label><br>
        <textarea id="message" name="message" rows="5" required></textarea><br><br>
        
        <button type="submit" name="submit">Wyślij</button>
    </form>
    
    <h1>Formularz Wysyłania E-maila</h1>
    <form action="send_email.php" method="POST">
        <label for="email">Twój e-mail:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="temat">Temat:</label>
        <input type="text" id="temat" name="temat" required><br><br>
        
        <label for="tresc">Treść wiadomości:</label><br>
        <textarea id="tresc" name="tresc" rows="5" required></textarea><br><br>
        
        <button type="submit" name="submit">Wyślij wiadomość</button>
    </form>

    <h1>Przypomnienie Hasła</h1>
    <form action="reminder.php" method="POST">
        <label for="email">Wprowadź swój adres e-mail:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <button type="submit" name="submit">Przypomnij hasło</button>
    </form>
</body>
</html>


