<?php
function PokazKontakt() {
    return '
    <form action="contact.php" method="POST">
        <label for="name">Imię:</label>
        <input type="text" id="name" name="name" required><br>
        
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="message">Wiadomość:</label><br>
        <textarea id="message" name="message" rows="5" required></textarea><br>
        
        <button type="submit" name="submit">Wyślij</button>
    </form>';
}

function PrzypomnijHaslo($email) {
    $subject = "Przypomnienie hasła";
    $message = "Twoje hasło do panelu admina to: <tutaj hasło>";
    $headers = "From: admin@moja-strona.pl";

    mail($email, $subject, $message, $headers);
}

function wyslijMailkontakt($odbiorca)
{
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email']))
    {
        echo "Nie podano wszystkich wymaganych danych!";
    }
    else
    {
        $mail['subject'] = $subject;
        $mail['body'] = $body;
        $mail['recipient'] = $recipient; //czyli wy jesteśmy odbiorcą, jeżeli tworzymy formularz kontaktowy
        $header = "From: Formularz kontaktowy <"; $mail['sender'] .=">";
        $header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding: ";
        $header .= "X-Mailer: PHP mail 1.2\n";
        $header .= "X-Priority: 3\n";
        $header .= "Return-Path: <"; $mail['sender'] .">";

        mail($mail['recipient'], $mail['subject'], $mail['body'], $header);

        echo "Wiadomość wysłana!";
    }
}

?>
