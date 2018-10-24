<?php
/**
 * Created by PhpStorm.
 * User: ericp
 * Date: 26.09.2018
 * Time: 13:13
 */

session_start();
require_once ('inc/dbconnector.php');

$user = '';
$error = '';

if(isset($_SESSION['login']) && $_SESSION['login'] == 1){

    $user = $_SESSION['username'];
}else{
    $error .= "Du bist nicht eingeloggt. Du musst dich anmelden um bei einem Event teilzunehmen. <br>";
}

if(!isset($_GET['eventid'])){
    // event id must me choosen
    header('Location: index.php');
}else{
    //check if event exists
    $eventid = trim(htmlspecialchars($_GET['eventid']));
    $query = "Select title, description, start, end, deadline, price, maxPeople from event where id = '" . $eventid . "'";
    $stmt = $db -> query($query);

    if( $stmt ->rowCount() > 0) {
        $row = $stmt->fetch();

        $title = $row['title'];
        $description= $row['description'];
        $start = $row['start'];
        $end = $row['end'];
        $deadline = $row['deadline'];
        $price = $row['price'];
        $maxPeople = $row['maxPeople'];

        //check force sign in
        if(isset($_GET['force']) && $_GET['force'] == 1){

            $userid = $_SESSION['id'];
            $insert = "Insert into event_has_user (event_id, user_id, state) values ('$eventid', '$userid', '$surename', '$hash')";
        }

    }else{
        $error .= "Dieser Event existiert nicht. <br>";
    }

}

?>

<!DOCTYPE html>
<html lang="de-CH">
<head>
    <title>Anmelde-Tool: Event</title>

</head>

<body>

    <header>
        <h1> Event</h1>

    </header>

    <nav>
        <?php include_once ('inc/nav.php'); ?>
    </nav>

    <main>
        <?php
        if(isset($user)){
            echo "<p> Hallo, " .  $user . '</p>';
        }

        if($error != ''){
            echo "<p> $error </p>";
        }

        echo '<div>';
            echo "\n\t\t\t" . '<h2>' . $row['title'] . '</h2> ' . "\n\t\t\t\t";
            if(!empty($description)){
                echo '<p>' . $row['description'];
                }
            echo '<p>Von ' . date_format(date_create($start), 'd.m.Y H:i') .  ' bis ' . date_format(date_create($end), 'd.m.Y H:i');
            echo '<p>Anmeldeschluss: ' . date_format(date_create($deadline), 'd.m.Y H:i');
            echo '<p>Kosten: ' . $price. ' CHF';

            if(!empty($maxPeople)){
                echo '<p> Maximale Anzahl Teilnehmer: ' . $maxPeople;
            }

        echo '</div>';

        echo '<p> Willst du dich wirklich für diesen Event anmelden? </p>';

        echo '<form method="get" action="event.php">';
        echo '<input type="hidden" name="eventid" value="' . $eventid . '">';
        echo '<input type="hidden" name="force" value="1">';
        echo '<input type="submit" value="Anmelden">';
        echo '</form>';
        ?>
    </main>

</body>

</html>

