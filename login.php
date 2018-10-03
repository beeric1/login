<?php
/**
 * Created by PhpStorm.
 * User: ericp
 * Date: 26.09.2018
 * Time: 13:42
 */


$username = '';
$error = '';

if(!empty($_POST)){

    //Check length of input parameters

    if(!empty($_POST['username'])){

        $username = trim(htmlspecialchars($_POST['username']));
    }else{
        $error .= "Benutzername muss ausgefüllt sein <br>";
    }

    if(!empty($_POST['password'])){

        $password = trim(htmlspecialchars($_POST['password']));
    }else{
        $error .= "Passwort muss ausgefüllt sein <br>";
    }

    //no errors -> check in db
    if(empty($error)){

        //check if username on db, if yes validate password with hash

        $query = "Select password from user where username = '" . $username . "'";
        $stmt = $db -> query($query);
        $row = $stmt -> fetch();
        if( $row['count'] > 0){
            //username valid

        }else{
            //username invalid
            $error .= "Benutzername und/oder Passwort ungültig <br>" ;
            $username = '';
        }

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
    <title>Anmelde-Tool: Login</title>

</head>

<body>
        <h1>Event-Anmelde-: Login</h1>
        <p>Melde dich bitte an:</p>


        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

            <label>
                Benutzername: <br>
                <input type="text" name="username" value="<?php echo $username?>">
            </label>
            <br>
            <label>
                Passwort:<br>
                <input type="password" name="password">
            </label>
            <br>
            <input type="submit" value="Anmelden">

        </form>


</body>
</html>