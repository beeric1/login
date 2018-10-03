<?php
/**
 * Created by PhpStorm.
 * User: ericp
 * Date: 26.09.2018
 * Time: 13:13
 */

session_start();

if(isset($_SESSION['login']) && $_SESSION['login'] == 1){
    echo "Hallo, " .  $_SESSION['user'];
}else{
    echo "Du bist nicht eingeloggt";
}
