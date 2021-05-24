<?php
require_once "config.php";
session_start();
$query = "select * from coords";
$results = mysqli_query($mysqli, $query);
$username = "";
echo "<br>";

//check for login
if(!isset($_SESSION["loggedin"])){
    header("location: login.php");
}

//Check for admin
if ($_SESSION["isAdmin"] === 1) {
    echo "<a style='margin-left: 15px;'>Username : " . $_SESSION["username"] . " (Administrator)</a>";
} else {
    echo "Username : " . $_SESSION["username"];
}

if (isset($_POST['logout'])) {
    session_start();
    session_destroy();
    header("location: login.php");
    exit;
} elseif (isset($_POST["adminPanel"])) {
    header("location: adminPanel.php");
}

?>

<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="homeStyle.css">

</head>

<body>
    <form method="POST" style="float: right; margin-right: 15px;">
        <?php
        if ($_SESSION["isAdmin"] === 1)
            echo "<button class='btn btn_pad btn-secondary' value='adminPanel' type='submit' name='adminPanel'> Admin Panel </button>";
        ?>
        <button class="btn btn_pad btn-secondary" value="logout" type="submit" name="logout">Logout</button>
    </form>

    <table style="width: 95%; margin: 50px auto 50px auto;">

        <tr>
            <th>ID</th>
            <th>Nume</th>
            <th>Latitudine</th>
            <th>Longitudine</th>
            <th>Culoare</th>
            <th>Dimensiune</th>
        </tr>
        <?php
        while ($rows = mysqli_fetch_assoc($results)) {
            echo "<tr>";

            foreach ($rows as $r) {
                if($r[0] === '#'){
                    echo "<td style='text-align: center; background-color: ".$r."'>" . $r . "</td>";
                }else{
                    echo "<td style='text-align: center;'>" . $r . "</td>";
                }
            }

            echo "</tr>";
        }
        ?>

    </table>
    <div style=" width: 15%; margin: 0px auto 0px auto;">
    <a href="map.php" class="btn btn-secondary center" style="text-align: center; width: 100%;">Go to map</a>
    </div>

</body>

</html>