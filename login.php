<?php
/**
 * Created by PhpStorm.
 * User: ericp
 * Date: 26.09.2018
 * Time: 13:42
 */

require_once ('inc/dbconnector.php');

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

        $query = "Select password, id, isadmin from user where username = '" . $username . "'";
        $stmt = $db -> query($query);

        if( $stmt ->rowCount() > 0){
            $row = $stmt -> fetch();
            $password_hash = $row['password'];

            if(password_verify($password,$password_hash)){
                //valid password -> save session
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['login'] = true;
                $_SESSION['id'] = $row['id'];
                $_SESSION['admin'] = $row['isadmin'];



                // back to index page
                header('Location: index.php');

            }else{
                //invalid password
                $error .= "Benutzername und/oder Passwort ungültig <br>" ;
            }

        }else{
            //username invalid
            $error .= "Benutzername und/oder Passwort ungültig <br>" ;
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

        <header>
            <h1>Event-Anmelde-: Login</h1>
            <p>Melde dich bitte an:</p>
        </header>

        <nav>
            <?php include_once ('inc/nav.php'); ?>
        </nav>

        <main>
            <?php
            if($error != ''){
                echo "<p> $error </p>";
            }
            ?>


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
        </main>


</body>
</html>