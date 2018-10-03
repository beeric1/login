<?php
/**
 * Created by PhpStorm.
 * User: ericp
 * Date: 26.09.2018
 * Time: 10:56
 */

echo '<ul>';
echo    '<li><a href="index.php">Startseite</a></li>';
if(isset($_SESSION['login']) && $_SESSION['login']){
    echo '<li><a href="logout.php">Logout</a></li>';
    echo '<li><a href="user.php">Mein Profil</a></li>';
}else{
    echo '<li><a href="login.php">Login</a></li>';
    echo '<li><a href="register.php">Registrieren</a></li>';
}
echo '</ul>';

?>


