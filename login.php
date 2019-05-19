<?php
require_once"pdo.php" ;

session_start();

// TODO: Array to be removed, switched by a database.
$hash = '1a52e17fa899cf40fb04cfc42e6352f1';
$salt = 'XyZzy12*_';



if (isset($_POST['email']) || isset($_POST['passwd'])){
    unset($_SESSION['email']);
    $email =!empty($_POST['email']) ?$_POST['email']: ''  ;
    $passwd = !empty($_POST['passwd']) ?$_POST['passwd']: '';

    if (!empty($email)&&!empty($passwd)){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['failure'] = "Email must have an at-sign (@)";
            header('Location:login.php');
            return;
        }
        $check = hash('md5', $salt . $passwd);


        if ( $check === $hash ) {
            echo "<p>Login success.</p>\n";
            $_SESSION['email'] = $_POST['email'];
            header("Location:autos.php");
            error_log("Login success ".$email);
            return;
        } else {
            $_SESSION['failure'] = 'Incorrect password';
            error_log("Login fail ".$email." $check");
            header('Location:login.php');
            return;

        }
    }else{
        $_SESSION['failure'] = "Email and passwd are required " ;
        header('Location: login.php');
        return;
    }


    die("Acess denied");

}
?>
<html>
<head>

<!-- Latest compiled and minified CSS -->
<link rel='stylesheet' href="css/style.css">
<title>Vincent Hunt's Login Page</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<form method="POST">
<label for="nam">Email</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="password" name="passwd" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>

    <?php

     error_reporting(E_ALL ^ E_NOTICE);

        if ($_SESSION['failure'] !== false)  echo('<p style="color:#ff1c29;">' .$_SESSION['failure'].'</p>');
        unset($_SESSION['failure']);

    ?>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the three character name of the
programming language used in this class (all lower case)
followed by 123. -->
</p>
</div>
</body>
