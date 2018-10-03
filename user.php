<?php
/**
 * Created by PhpStorm.
 * User: ericp
 * Date: 03.10.2018
 * Time: 11:03
 */
$user = '';
session_start();

if($_SESSION['login']){
    $user = $_SESSION['username'];
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

</body>

</html>