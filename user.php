<?php
/**
 * Created by PhpStorm.
 * User: ericp
 * Date: 03.10.2018
 * Time: 11:03
 */

require_once ('inc/dbconnector.php');
$user = '';
session_start();

if($_SESSION['login']){
    $user = $_SESSION['username'];
    $userid =  $_SESSION['id'];

    //get user details
    $query = "Select forename, surename from user where id = '" . $userid . "'";
    $stmt = $db -> query($query);
    if( $stmt ->rowCount() > 0){
        //query successful
        $row = $stmt -> fetch();
        $forename = $row['forename'];
        $surename = $row['surename'];
    }else{
        $error .= "Query fehlgeschlagen (User exisitiert nicht) <br>";
    }



}
?>

<!DOCTYPE html>
<html lang="de-CH">
<head>
    <title>Anmelde-Tool: Benutzer</title>

</head>

<body>

    <header>

        <h1>Benutzerprofil</h1>
    </header>

    <nav>
        <?php include_once ('inc/nav.php'); ?>
    </nav>


    <?php if(!empty($user)){

        echo "<p> Hallo, $user </p>";

    }  ?>

    <main>
        <h2>Deine Angaben</h2>
        <p>Benutzername: <?php echo " $user"?></p>
        <p>Vorname: <?php echo " $forename"?></p>
        <p>Nachname: <?php echo " $surename"?></p>
        <?php
            if($_SESSION['admin'] == 1){
                echo "<p>Du bist Admin!</p>";
            }
        ?>

        <h2>Deine Events</h2>

        <?php

        //get events
        $query = "SELECT title, description, start, end, price , deadline, maxPeople from event join event_has_user u on event.id = u.event_id where u.state = 1 and u.user_id = '" . $userid . "'";
        $stmt = $db -> query($query);
        $resultArray = $stmt -> fetchAll();

        if($resultArray !=null){

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

                /*
                echo '<form method="get" action="event.php">';
                echo '<input type="hidden" name="eventid" value="' . $row['id'] . '">';
                echo '<input type="submit" value="Anmelden">';
                echo '</form>';
                */

                echo '</div>';
            }

        }else{
            echo '<p> Du hast dich für keinen Event angemeldet.</p>';
        }

        ?>

    </main>

</body>

</html>