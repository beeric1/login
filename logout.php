<?php
/**
 * Created by PhpStorm.
 * User: ericp
 * Date: 03.10.2018
 * Time: 11:03
 */

$message = '';

session_start();

if($_SESSION['login']){
    //logged in
    $_SESSION = array();
    session_destroy();
    $message = "Du wurdest erfolgreich ausgeloggt.";
}else{
    //not logged in
    $message = "Du bist nicht angemeldet.";
}

?>

<!DOCTYPE html>
<html lang="de-CH">
<head>
    <title>Anmelde-Tool: Logout</title>

</head>

<body>

    <h1>Logout</h1>

    <nav>
        <?php include_once ('inc/nav.php'); ?>
    </nav>

    <main>
        <?php echo $message; ?>
    </main>

</body>

</html>