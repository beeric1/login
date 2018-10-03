<?php
/**
 * Created by PhpStorm.
 * User: ericp
 * Date: 26.09.2018
 * Time: 13:13
 */

session_start();

?>

<!DOCTYPE html>
<html lang="de-CH">
<head>
    <title>Anmelde-Tool: Event</title>

</head>

<body>

    <header>
        <h1> Event Blabla</h1>

    </header>

    <nav>
        <?php include_once ('inc/nav.php'); ?>
    </nav>

    <main>
        <?php
            if(isset($_SESSION['login']) && $_SESSION['login'] == 1){
                echo "Hallo, " .  $_SESSION['user'];
            }else{
                echo "Du bist nicht eingeloggt";
            }
        ?>
    </main>

</body>

</html>

