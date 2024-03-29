<?php
/**
 * Created by PhpStorm.
 * User: ericp
 * Date: 26.09.2018
 * Time: 10:53
 */

require_once ('inc/dbconnector.php');

$logedin = false;
$user = null;

session_start();

if($_SESSION['login'] = true && isset($_SESSION['username'])){
    $logedin = true;
    $user = $_SESSION['username'];
    $userid = $_SESSION['id'];
}

//

?>

<!DOCTYPE html>
<html lang="de-CH">
<head>
    <title>Anmelde-Tool: Events</title>

</head>

<body>
    <header>
        <h1>Event-Anmelde-Tool</h1>

        <?php if(!empty($user)){

            echo "<p> Hallo, $user </p>";
        }  ?>

    </header>

    <nav>
        <?php include_once ('inc/nav.php'); ?>
    </nav>

    <main>
        <h2>Events</h2>
        <?php
            //nur events, bei denen man sich noch anmelden kann (deadline noch nicht erreicht , max teilnehmer zahl nicht erreicht, nicht schon angemeldet)
            if(empty($user)){
                //Nicht eingeloggt
                $query = "Select id, title, description, start, end, deadline, price, maxPeople from event 
                    where deadline > NOW()
                    and (maxPeople is null or maxPeople > (SELECT count(user_id) from event_has_user where event_id = id))";
            }else{
                //eingeloggt
                $query = "Select id, title, description, start, end, deadline, price, maxPeople from event 
                    where deadline > NOW()
                    and (maxPeople is null or maxPeople > (SELECT count(user_id) from event_has_user where event_id = id))
                    and " . $userid . " NOT IN (SELECT user_id from event_has_user where state = 1 and event_id = id)";
            }

            $result = $db -> query($query);
            $resultArray = $result -> fetchAll();

            //Verbindung trennen
            $db = NULL;

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

                    echo '<form method="get" action="event.php">';
                    echo '<input type="hidden" name="eventid" value="' . $row['id'] . '">';
                    echo '<input type="submit" value="Anzeigen">';
                    echo '</form>';

                    echo '</div>';
                }

            }else{
                echo '<p> momentan gibt es keine Events</p>';
            }
        ?>
    </main>


</body>

</html>
