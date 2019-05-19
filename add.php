
<?php
require_once('pdo.php');
require_once('curl.php');

session_start();

if (!isset($_SESSION['email'])){
    die("ACCESS DENIED");
}

if (isset($_POST['cancel'])){
    header("Location:autos.php");
    return;
}

if (isset($_POST['add'])){
    $_error = false;

if (!empty($_POST['imageURL'])){
    if(substr($_POST['imageURL'],0,7)!="http://"&&substr($_POST['imageURL'],0,8)!="https://"){
        $_SESSION['error'] = "Invalid URL, missing http protocol";
        $_error = true;
    }
    else if(remote_file_exists($_POST['imageURL']) === false){
        $_SESSION['error'] = "Invalid URL, conncetion failed";
        $_error = true;
    }
}
elseif( !is_numeric($_POST['year']) || !is_numeric($_POST['mileage']) ){
        $_SESSION['error'] = 'Mileage and year must be numeric';
        $_error = true;
    } elseif( strlen($_POST['make']) < 1 ){
        $_SESSION['error'] = 'Make is required';
        $_error = true;
    }
    if( $_error == true ){
        $_SESSION['imageURL'] = $_POST['imageURL'];
        $_SESSION['make'] = $_POST['make'];
        $_SESSION['year'] = $_POST['year'];
        $_SESSION['mileage'] = $_POST['mileage'];
        header('Location: add.php');
        return;


}

    $imageURL = !empty($_POST['imageURL'])? $_POST['imageURL']:null;

    try{
        $stmt = $pdo->prepare('INSERT INTO autos  (imageURL, make, year, mileage)
            VALUES (:imageURL, :make, :year, :mileage)');
        $stmt->execute(array(
                ':imageURL' => $imageURL,
                ':make' => $_POST['make'],
                ':year' => $_POST['year'],
                ':mileage' => $_POST['mileage'])
        );
    } catch (Exception $e){
        echo("Internal error, please contact support");
        error_log("SQL error =".$e->getMessage());
        return;
    }


    $_SESSION['success']= 'Record added ';
    header("Location:autos.php");
    return;
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <title>AutosDB - Vincent</title>
</head>
<body>




<form method="post">
    <p>Make:
        <input type="text" name="make" size="60" value="<? isset($_SESSION['make'])?htmlentities($_SESSION['make']):'' ?>"/></p>
    <p>Year:
        <input type="text" name="year" value="<? isset($_SESSION['year'])?htmlentities($_SESSION['year']):'' ?>"/></p>
    <p>Mileage:
        <input type="text" name="mileage" value="<? isset($_SESSION['mileage'])?htmlentities($_SESSION['mileage']):'' ?>"/></p>
    <p>imageURL(optional):
        <input type="text" name="imageURL" value="<? isset($_SESSION['imageURL'])?htmlentities($_SESSION['imageURL']):'' ?>" /></p>
    <input type="submit" class ="button" name="add"  value="Add">
    <input type="submit" class="button" name="cancel" value="Cancel">
</form>
<?php
if (isset($_SESSION['error'])&&$_SESSION['error']!= false){
    echo ("<p id='error'>");
    echo $_SESSION['error'];
    echo ("</p>");

    unset($_SESSION['error']);

    unset($_SESSION['imageURL']);
    unset($_SESSION['make']);
    unset($_SESSION['model']);
    unset($_SESSION['year']);
    unset($_SESSION['mileage']);
}
?>

</body>


</html>
