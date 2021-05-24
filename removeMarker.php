<?php
//Remove marker from database
require_once "config.php";
$markerID = $_GET['ID'];

echo "Remove marker with id = " . $markerID;
$sql = "DELETE FROM coords WHERE ID=".$markerID;

if ($mysqli->query($sql) === TRUE) {
    echo "Record deleted successfully";
    header("location: adminPanel.php");
} else {
    echo "Error deleting record: " . $mysqli->error;
}

?>

<html>

</html>