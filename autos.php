<?php

require_once ('pdo.php');

session_start();

if(! isset($_SESSION['email'])){
    die("Please login first ");
}


?>


<html>
<head>
<title>Mr.Vincent Automobile Tracker</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>
<body>
<div class="container">
<h1>Tracking Autos for <?= htmlentities($_SESSION['email'])?> </h1>


    <?php  if ($_SESSION['falure'] !== false){
    echo('<p style="color: red;">'.htmlentities($_SESSION['falure'])."</p>\n");
    unset($_SESSION['falure']);
} else if ( $_SESSION['success'] !== false ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
        unset($_SESSION['success']);
    }


    ?>

<h2>Automobiles</h2>

    <?php
    require_once "pdo.php";
    $stmt = $pdo->query("SELECT * FROM autos order by make");
    if ($stmt->rowCount() ==0 ){
        echo '<p> No rows found </p>';
    }
    else{    echo "<table border='1'>
                        <thead><tr>
                            <th> Make </th>
                            <th> Year </th>
                            <th> Mileage </th>
                            <th> Action </th>
                        </tr></thead>
                        <tbody>";
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ( $rows as $row ) {
            echo '<tr><td>';
            if (isset($row['imageURL'])) {
                echo '<a target="blank" href='.htmlentities($row['imageURL']).'> '.htmlentities($row['make']).' </a>';
            } else {
                echo(htmlentities($row['make']));
            }
            echo('</td><td>');
            echo(htmlentities($row['year']));
            echo('</td><td>');
            echo(htmlentities($row['mileage']));
            echo('</td><td>');
            echo('<a href="edit.php?auto_id='.$row['auto_id'].'"> Edit </a> / <a href="delete.php?auto_id='.$row['auto_id'].'"> Delete </a>');
            echo('</td></tr>');
        }

        echo "	</tbody>
                    </table>";
    }
?>

    <p>
        <a href='add.php'>Add New Entry</a> || <a href='logout.php'>Logout</a>
    </p>

</html>
