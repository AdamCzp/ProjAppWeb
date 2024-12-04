<?php
function PokazPodstrone($id)
{
    $id_clear = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');

    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    
    $result = mysqli_query($GLOBALS['link'], $query);
    $row = mysqli_fetch_array($result);

    if (empty($row['id'])) {
        $web = '<div>Nie znaleziono strony</div>';
    } else {
        $web = $row['page_content'];
    }

    return $web;
}

?>