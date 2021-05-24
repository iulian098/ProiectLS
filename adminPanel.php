<?php

require_once "config.php";
session_start();
$query = "select * from coords";
$results = mysqli_query($mysqli, $query);


//Check for admin
if ($_SESSION['isAdmin'] === 0 || empty($_SESSION['isAdmin'])) {
    header("location: home.php");
    die("No Admin");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Create admin
    if (isset($_POST['makeAdmin']) || isset($_POST['removeAdmin'])) {
        makeAdmin($mysqli);
    }

    //Add marker
    if (isset($_POST['addMarker'])) {
        addMarker($mysqli);
    }

    //go back to home
    if (isset($_POST["back"])) {
        header("location: home.php");
    }
}

function makeAdmin($_mysqli)
{
    $param_username = "";
    $admin = 0;
    global $username_err, $username;
    $sql = "UPDATE users SET isAdmin = ? WHERE username=?;";
    if (empty($_POST["username"])) {
        $username_err = "Username is empty";
    } else {
        $username = trim($_POST["username"]);
        if ($stmt = $_mysqli->prepare($sql)) {
            $stmt->bind_param("is", $admin, $param_username);

            $param_username = $username;

            if (isset($_POST['makeAdmin'])) {
                $admin = 1;
            } elseif (isset($_POST['removeAdmin'])) {
                $admin = 0;
            }

            if ($stmt->execute()) {
                if ($admin === 1) {
                    echo "<p width=100% style='text-align: center;'>" . $username . " is now Admin</p>";
                } else {
                    echo "<p width=100% style='text-align: center;'>" . $username . " is removed from Admin</p>";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
    }
}

function addMarker($_mysqli)
{
    global $mkName_err, $mkLat_err, $mkLong_err, $mkDim_err, $mkName, $mkLat, $mkLong, $mkCuloare, $mkDim;


    if (empty($_POST["markerName"])) {
        $mkName_err = "Marker name is empty";
    } elseif (empty($_POST["lat"])) {
        $mkLat_err = "Lat is empty";
    } elseif (empty($_POST["long"])) {
        $mkLong_err = "Long is empty";
    } elseif (empty($_POST["dim"]) || $_POST["dim"] === 0) {
        $mkDim_err = "Dim is empty";
    } else {

        //Assign variables
        $mkName = trim($_POST["markerName"]);
        $mkLat = $_POST["lat"];
        $mkLong = $_POST["long"];
        $mkDim = $_POST["dim"];
        $mkCuloare = $_POST["color"];

        $sql = "INSERT INTO coords (nume, lat, longi, culoare, dim) VALUES ('" . $mkName . "', " . $mkLat . ", " . $mkLong . ", '" . $mkCuloare . "', " . $mkDim . ")";

        //Run sql
        if ($stmt1 = $_mysqli->prepare($sql)) {

            if ($stmt1->execute()) {
                echo "<p width=100% style='text-align: center;'>Marker " . $mkName . " adaugat</p>";
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            $stmt1->close();

        } else {
            echo "Error";
        }

        //Check for errors
        if ($_mysqli->errno) {
            die("Could not insert record into table: " . $_mysqli->error . "<br/>");
        }
        header("Refresh: 0");
    }
    mysqli_close($_mysqli);
}
?>

<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="homeStyle.css">
</head>

<body>
    <form method="POST">
        <button class="btn btn-primary" style="margin: 10px;" name="back">Back to home</button>
    </form>
    <div style="margin-left: 15px; margin-right: 15px;">
        <h3>Adauga Admin</h3>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form-inline" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" style="margin-left: 10px;">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-secondary" value="Make Admin" name="makeAdmin" style="margin-left: 10px;">
                <input type="submit" class="btn btn-secondary" value="Remove Admin" name="removeAdmin" style="margin-left: 10px;">
            </div>
        </form>
        <div style="display: flex;">
            <div style="width: 25%; flex: 1;">

                <h3 style="margin-top: 25px;">Adauga Marker</h3>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form">
                    <div class="form-group">
                        <label>Nume</label>
                        <input type="text" name="markerName" class="form-control <?php echo (!empty($mkName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $mkName; ?>">
                        <span class="invalid-feedback"><?php echo $mkName_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Latitudine</label>
                        <input type="number" step="0.0000001" name="lat" class="form-control <?php echo (!empty($mkLat_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $mkLat; ?>">
                        <span class="invalid-feedback"><?php echo $mkLat_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Longitudine</label>
                        <input type="number" step="0.0000001" name="long" class="form-control <?php echo (!empty($mkLong_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $mkLong; ?>">
                        <span class="invalid-feedback"><?php echo $mkLong_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Culoare</label>
                        <input type="color" name="color" style="width: 125px;" class="form-control <?php echo (!empty($mkCol_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $mkCuloare; ?>">
                        <span class="invalid-feedback"><?php echo $mkCol_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Dimensiune</label>
                        <input type="number" name="dim" class="form-control <?php echo (!empty($mkDim_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $mkDim; ?>">
                        <span class="invalid-feedback"><?php echo $mkDim_err; ?></span>
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-secondary" value="Adauga" name="addMarker">
                    </div>
                </form>
            </div>
            <div style="width: 65%; margin-left: 25px; margin-bottom: 15px;">
                <h3 style="margin-top: 25px;">Lista markere</h3>

                <table style="width: 100%; margin-top: 50px;">

                    <tr>
                        <th>ID</th>
                        <th>Nume</th>
                        <th>Latitudine</th>
                        <th>Longitudine</th>
                        <th>Culoare</th>
                        <th>Dimensiune</th>
                        <th>Sterge</th>
                    </tr>
                    <?php
                    while ($rows = mysqli_fetch_assoc($results)) {
                        echo "<tr>";

                        foreach ($rows as $r) {
                            echo "<td style='text-align: center;'>" . $r . "</td>";
                        }

                        echo "<td style='text-align: center;'><form method='POST' style='align-items:center;'><input type='image' id='image' alt='Login' formaction='removeMarker.php?ID=" . $rows['ID'] . "' src='img/remove.png'></form></td>";
                        echo "</tr>";
                    }
                    ?>

                </table>
            </div>
        </div>
    </div>

</body>

</html>