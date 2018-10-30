<?php
/**
 * Created by PhpStorm.
 * User: ericp
 * Date: 30.10.2018
 * Time: 10:00
 */

require_once ('inc/dbconnector.php');
$user = '';
session_start();

if(isset($_SESSION['login']) && $_SESSION['login'] == 1){
    $user = $_SESSION['username'];
    $userid =  $_SESSION['id'];

}else{
    //not logged in
    header('Location: login.php');
}

$title = '';
$description = null;
$startdate = '';
$starttime = '12:00';
$enddate = '';
$endtime = '12:00';
$dealinedate = '';
$dealinetime = '12:00';
$price = 0;
$maxPeople = null;



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
                    <textarea rows="4" cols="50" value="<?php echo $description?>"></textarea>
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
                    <input type="date" name="deadlinedate" value="<?php echo $dealinedate?>"> <input type="time" name="dealinetime" value="<?php echo $dealinetime?>">
                </label>
                <br>
                <label>
                    Preis: <br>
                    <input type="number" name="price" value="<?php echo $price?>">
                </label>
                <br>
                <label>
                    Maximale Anzahl Teilnehmer: <br>
                    <input type="number" name="maxPeople" value="<?php echo $maxPeople?>">
                </label>
                <br>
                <br>
                <input type="submit" value="Erstellen">
            </form>

        <h2>Alle Events</h2>

    </main>

</body>

</html>