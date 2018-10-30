<?php
/**
 * Created by PhpStorm.
 * User: ericp
 * Date: 30.10.2018
 * Time: 21:36
 */

require_once ('inc/dbconnector.php');
$user = '';
$error = '';
session_start();

if(isset($_SESSION['login']) && $_SESSION['login'] == 1){
    $user = $_SESSION['username'];
    $userid =  $_SESSION['id'];

    if(!isset($_GET['id'])){
        //no event id
        header('Location: admin.php');
    }else{
        $eventid = $_GET['id'];
    }
}else{
    //not logged in
    header('Location: login.php');
}

?>

<!DOCTYPE html>
<html lang="de-CH">
<head>
    <title>Anmelde-Tool: Admin</title>

</head>

<body>

    <header>
        <h1>Event administrieren</h1>
    </header>

    <nav>
        <?php include_once ('inc/nav.php'); ?>
    </nav>


    <?php if(!empty($user)){

        echo "<p> Hallo, $user </p>";

        if(isset($error)){
            echo "<p> $error</p>";
        }

    }  ?>

    <main>
        <h2>Details</h2>
        <?php
        // get details
        $query = "Select title, description, start, end, deadline, price, maxPeople from event where id = $eventid";
        $result = $db -> query($query);
        $resultArray = $result -> fetchAll();
        foreach($resultArray as $row){
            echo '<div>';
            echo "\n\t\t\t" . '<h3>' . $row['title'] . '</h3> ' . "\n\t\t\t\t";
            if($row['description'] != null){
                echo '<p>' . $row['description'];
            }
            echo '<p>Von ' . date_format(date_create($row['start']), 'd.m.Y H:i') .  ' bis ' . date_format(date_create($row['end']), 'd.m.Y H:i');
            echo '<p>Anmeldeschluss: ' . date_format(date_create($row['deadline']), 'd.m.Y H:i');
            echo '<p>Kosten: ' . $row['price'] . ' CHF';

            if($row['maxPeople'] != null){
                echo '<p> Maximale Anzahl Teilnehmer: ' . $row['maxPeople'];
            }

            echo '</div>';
        }
        ?>

        <h3>Anmeldungen</h3>
        <?php
        // get teilnehmer
        $query = "Select forename, surename, ehu.state, ehu.documents, ehu.payed from user join event_has_user ehu on user.id = ehu.user_id where ehu.event_id = $eventid";
        $result = $db -> query($query);
        $resultArray = $result -> fetchAll();

        echo '<table>';
        echo '<tr><th>Vorname</th><th>Nachname</th><th>Angemeldet</th> <th>Dokumente</th> <th>Zahlung</th></tr>';
        foreach($resultArray as $row){
            $angemeldet = 'nein';
            $dokument = 'nein';
            $zahlung = 'nein';
            if($row['state'] == 1){$angemeldet = 'ja';}
            if($row['documents'] == 1){$dokument = 'ja';}
            if($row['payed'] == 1){$zahlung = 'ja';}

            echo "<tr><td>$row[forename]</td><td>$row[surename]</td><td>$angemeldet</td><td>$dokument</td><td>$zahlung</td></tr>";
        }
        echo '</table>';

        //Verbindung trennen
        $db = NULL;
        ?>


    </main>

</body>

</html>
