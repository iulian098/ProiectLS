<?php
require_once "config.php";
//require("phpsqlajax_dbinfo.php");

// Start XML file, create parent node
$doc = new DOMDocument("1.0");
$node = $doc->createElement("markers");
$parnode = $doc->appendChild($node);

// Select all the rows in the markers table
$query = "SELECT * FROM coords";
$result = mysqli_query($mysqli, $query);

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each
while ($row = @mysqli_fetch_assoc($result)){
  // Add to XML document node
  $node = $doc->createElement("marker");
  $newnode = $parnode->appendChild($node);

  $newnode->setAttribute("id", $row['ID']);
  $newnode->setAttribute("name", $row['nume']);
  $newnode->setAttribute("lat", $row['lat']);
  $newnode->setAttribute("lng", $row['longi']);
  $newnode->setAttribute("color", $row['culoare']);
  $newnode->setAttribute("dim", $row['dim']);
}

$xmlfile = $doc->saveXML();
echo $xmlfile;

?>