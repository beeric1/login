<?php
/**
 * Created by PhpStorm.
 * User: ericp
 * Date: 30.10.2018
 * Time: 10:00
 */

require_once ('inc/dbconnector.php');
$user = '';
$error = '';
session_start();

if(isset($_SESSION['login']) && $_SESSION['login'] == 1){
    $user = $_SESSION['username'];
    $userid =  $_SESSION['id'];

}else{
    //not logged in
    header('Location: login.php');
}

$title = '';
$description = '';
$startdate = '';
$starttime = '12:00';
$enddate = '';
$endtime = '12:00';
$deadlinedate = '';
$deadlinetime = '12:00';
$price = 0;
$maxPeople = '';

if(!empty($_POST)){

    //mandatory
    if(!empty($_POST['title'])){
        $title = trim(htmlspecialchars($_POST['title']));
    }else{
        $error .= "Titel muss ausgefüllt sein <br>";
    }

    //optional
    if(!empty($_POST['description'])){
        $description = htmlspecialchars($_POST['description']);
    }

    //mandatory
    if(!empty($_POST['startdate'])){
        $startdate = trim(htmlspecialchars($_POST['startdate']));
        if(!preg_match("([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))",$startdate)){
            $error .= "Startdatum ist kein Datum $startdate <br>";
            $startdate = '';
        }
    }else{
        $error .= "Startdatum muss ausgefüllt sein <br>";
    }
    //madatory
    if(!empty($_POST['starttime'])){
        $starttime = trim(htmlspecialchars($_POST['starttime']));
        if(!preg_match("/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/" ,$starttime)){
            $error .= "Startzeit ist keine Zeit $starttime <br>";
            $starttime = '';
        }
    }else{
        $error .= "Startzeit muss ausgefüllt sein <br>";
    }

    //mandatory
    if(!empty($_POST['enddate'])){
        $enddate = trim(htmlspecialchars($_POST['enddate']));
        if(!preg_match("([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))",$enddate)){
            $error .= "Enddatum ist kein Datum $enddate <br>";
            $enddate = '';
        }
    }else{
        $error .= "Enddatum muss ausgefüllt sein <br>";
    }
    //madatory
    if(!empty($_POST['endtime'])){
        $endtime = trim(htmlspecialchars($_POST['endtime']));
        if(!preg_match("/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/" ,$endtime)){
            $error .= "Endzeit ist keine Zeit $endtime <br>";
            $endtime = '';
        }
    }else{
        $error .= "Endzeit muss ausgefüllt sein <br>";
    }

    //mandatory
    if(!empty($_POST['deadlinedate'])){
        $deadlinedate = trim(htmlspecialchars($_POST['deadlinedate']));
        if(!preg_match("([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))",$deadlinedate)){
            $error .= "Anmeldeschluss-Datum ist kein Datum $deadlinedate <br>";
            $deadlinedate = '';
        }
    }else{
        $error .= "Anmeldeschluss-Datum muss ausgefüllt sein <br>";
    }
    //mandatory
    if(!empty($_POST['deadlinetime'])){
        $deadlinetime = trim(htmlspecialchars($_POST['deadlinetime']));
        if(!preg_match("/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/" ,$deadlinetime)){
            $error .= "Deadline-Zeit ist keine Zeit $deadlinetime <br>";
            $deadlinetime = '';
        }
    }else{
        $error .= "Teilenahmeschluss-Zeit muss ausgefüllt sein <br>";
    }

    //mandatory
    if(!empty($_POST['price']) || $_POST['price'] == 0){
        $price = trim(htmlspecialchars($_POST['price']));
        if(preg_match("/^\d+(\.\d{1,2})?$/",$price ) && $price < 10000){
            //ok
        }else{
            $error .= "Preis muss eine Zahl kleiner als 10000 sein: $price <br>";
            $price = 0;
        }
    }else{
        $error .= "Preis muss ausgefüllt sein <br>";
    }

    //optional
    if(!empty($_POST['maxPeople'])){
        $maxPeople = trim(htmlspecialchars($_POST['maxPeople']));
        if(preg_match("/^\d+$/",$maxPeople )){
            //ok
        }else{
            $error .= "Maximale Teilnehmeranzahl muss eine Zahl sein: $maxPeople <br>";
            $maxPeople = '';
        }
    }

    //everything ok, save in db
    if($error == ''){
        $start = $startdate . ' ' . $starttime . ':00';
        $end = $enddate . ' ' . $endtime . ':00';
        $deadline = $deadlinedate . ' ' . $deadlinetime . ':00';

        if($maxPeople == '' && $description == ''){
            $insert = "Insert into event (title, start, end, deadline, price) values ('$title', '$start', '$end', '$deadline', $price)";
        }elseif ($maxPeople == ''){
            $insert = "Insert into event (title, description, start, end, deadline, price) values ('$title', '$description', '$start', '$end', '$deadline', $price)";
        }elseif ($description == ''){
            $insert = "Insert into event (title, start, end, deadline, price, maxPeople) values ('$title', '$start', '$end', '$deadline', $price, $maxPeople)";
        }else{
            $insert = "Insert into event (title, description, start, end, deadline, price, maxPeople) values ('$title', '$description', '$start', '$end', '$deadline', $price, $maxPeople)";
        }


        if($db->exec($insert)){
            //insert ok
            header('Location: index.php');

        }else{
            $error .= "Insert fehlgeschlagen <br>";
        }
    }

}

if(!empty($_GET)){
    $eventid = htmlspecialchars($_GET['id']);
    $delete1 = "Delete from event where id = ". $eventid;
    $delete2 = "Delete from event_has_user where event_id = " . $eventid;
    if($db->exec($delete2)){
        //ok
        if($db->exec($delete1)){
            //ok
            header('Location: index.php');
        }else{
            $error .= "Teilnahmen löschen fehlgeschlagen <br>";
        }
    }else{
        $error .= "Löschen fehlgeschlagen <br>";
    }

}

?>


<!DOCTYPE html>
<html lang="de-CH">
<head>
    <title>Anmelde-Tool: Admin</title>

</head>

<body>

    <header>
        <h1>Admin-Seite</h1>
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
        <h2>Event erstellen</h2>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

                <label>
                    Titel: <br>
                    <input type="text" autofocus="autofocus" name="title" value="<?php echo $title?>">
                </label>
                <br>
                <label>
                    Beschreibung: (optional) <br>
                    <textarea rows="2" cols="30" name="description"><?php echo $description?></textarea>
                </label>
                <br>
                <label>
                    Start: <br>
                    <input type="date" name="startdate" value="<?php echo $startdate?>"> <input type="time" name="starttime" value="<?php echo $starttime?>">
                </label>
                <br>
                <label>
                    Schluss: <br>
                    <input type="date" name="enddate" value="<?php echo $enddate?>"> <input type="time" name="endtime" value="<?php echo $endtime?>">
                </label>
                <br>
                <label>
                    Anmeldeschluss: <br>
                    <input type="date" name="deadlinedate" value="<?php echo $deadlinedate?>"> <input type="time" name="deadlinetime" value="<?php echo $deadlinetime?>">
                </label>
                <br>
                <label>
                    Preis: <br>
                    <input type="number" min="0.00" step="0.01" name="price" value="<?php echo $price?>">
                </label>
                <br>
                <label>
                    Maximale Anzahl Teilnehmer: <br>
                    <input type="number" name="maxPeople" min="0.00" value="<?php echo $maxPeople?>">
                </label>
                <br>
                <br>
                <input type="submit" value="Erstellen">
            </form>

        <h2>Alle Events</h2>

        <?php
        // get all events
        $query = "Select id, title, description from event";
        $result = $db -> query($query);
        $resultArray = $result -> fetchAll();

        echo '<table>';
        echo '<tr><th>Titel</th><th>Beschreibung</th><th>Anzeigen</th><th>Löschen</th></tr>';
        foreach($resultArray as $row){
            echo "<tr><td>$row[title]</td> <td>$row[description]</td>  <td>";
            echo "<form method='get' action='eventadmin.php'> <input type='hidden' name='id' value='$row[id]'> <input type='submit' value='anzeigen'> </form></td>";
            echo "<td><form method='get' action='admin.php'> <input type='hidden' name='id' value='$row[id]'> <input type='submit' value='löschen'> </form></td></tr>";
        }
        echo "</table";
        ?>

    </main>

</body>

</html>