<?php
/**
 * Created by PhpStorm.
 * User: ericp
 * Date: 26.09.2018
 * Time: 13:51
 */

require_once ('inc/dbconnector.php');

$error = '';
$username = '';
$password = '';
$forename = '';
$surename = '';

if(!empty($_POST)){

    //Check length of input parameters

    if(!empty($_POST['username'])){
        if(strlen(trim($_POST['username'])) <= 50){
            $username = htmlspecialchars(trim($_POST['username']));

            //Check if username is not already taken
            $query = "Select count(username) as count from user where username = '" . $username . "'";
            $stmt = $db -> query($query);
            $row = $stmt -> fetch();
            if( $row['count'] > 0){
                $error .= "Benutzername bereits vergeben <br>" ;
                $username = '';
            }

        }else{
            $error .= "Benutzername ist zu lange (max 25 Zeichen) <br>";
        }
    }else{
        $error .= "Benutzername muss ausgefüllt sein <br>";
    }

    if(!empty($_POST['password'])){
        if(strlen(trim($_POST['password'])) <= 25 && strlen(trim($_POST['password'])) >= 8){
            $password = trim($_POST['password']);
        }else{
            $error .= "Passwort ist zu lange oder zu kurz (min 8 und max 25 Zeichen) <br>";
        }
    }else{
        $error .= "Passwort muss ausgefüllt sein <br>";
    }

    if(!empty($_POST['forename'])){
        if(strlen(trim($_POST['forename'])) <= 50){
            $forename = htmlspecialchars(trim($_POST['forename']));
        }else{
            $error .= "Vorname ist zu lange (max 50 Zeichen) <br>";
        }
    }else{
        $error .= "Vorname muss ausgefüllt sein <br>";
    }

    if(!empty($_POST['surename'])){
        if(strlen(trim($_POST['surename'])) <= 50){
            $surename = htmlspecialchars(trim($_POST['surename']));
        }else{
            $error .= "Nachname ist zu lange (max 50 Zeichen) <br>";
        }
    }else{
        $error .= "Nachname muss ausgefüllt sein <br>";
    }


    //no errors -> save in db
    if(empty($error)){

        //hash password
        $hash = password_hash($password,PASSWORD_DEFAULT);

        $insert = "Insert into user (username, forename, surename, password) values ('$username', '$forename', '$surename', '$hash')";
        if($db->exec($insert)){
            header('Location: login.php');
        }else{
            $error .= "Insert fehlgeschlagen <br>";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="de-CH">
<head>
    <title>Anmelde-Tool: Registration</title>

</head>

<body>
    <h1>Event-Anmelde-Tool: Registrieren</h1>
    <p>Fülle das Formular aus um dich zu registrieren. Alle Felder müssen ausgefüllt werden.</p>

    <?php
        if($error != ''){
            echo "<p> $error </p>";
        }
    ?>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

        <label>
            Benutzername: (max 25 Zeichen) <br>
            <input type="text" name="username" value="<?php echo $username?>">
        </label>
        <br>
        <label>
            Passwort: (min 8 und max 25 Zeichen) <br>
            <input type="password" name="password">
        </label>
        <br>
        <label>
            Vorname: (max 50 Zeichen) <br>
            <input type="text" name="forename" value="<?php echo $forename?>">
        </label>
        <br>
        <label>
            Nachname: (max 50 Zeichen) <br>
            <input type="text" name="surename" value="<?php echo $surename?>">
        </label>
        <br>
        <input type="submit" value="Registrieren">
        <input type="reset" value="Zurücksetzen">


    </form>


</body>
</html>
