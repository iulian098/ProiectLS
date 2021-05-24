<?php

require_once "config.php";
$query = "select * from coords";
$result = mysqli_query($mysqli, $query);
//$result = $mysqli->query("SELECT * FROM coords");
$result2 = $mysqli->query("SELECT * FROM coords");

if (isset($_POST["back"])) {
    header("location: home.php");
}


?>

<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
<!-- AIzaSyB6mtG07zGbd10U6EnuqEwqkaupsTTRKfs -->
    <form method="POST">
        <button class="btn btn-primary" style="margin: 10px;" name="back">Back to home</button>
    </form>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB6mtG07zGbd10U6EnuqEwqkaupsTTRKfs"></script>

    <script>
        var pinColor = "#00FF00";
        var pinSVG = "M10.453 14.016l6.563-6.609-1.406-1.406-5.156 5.203-2.063-2.109-1.406 1.406zM12 2.016q2.906 0 4.945 2.039t2.039 4.945q0 1.453-0.727 3.328t-1.758 3.516-2.039 3.070-1.711 2.273l-0.75 0.797q-0.281-0.328-0.75-0.867t-1.688-2.156-2.133-3.141-1.664-3.445-0.75-3.375q0-2.906 2.039-4.945t4.945-2.039z";
        var labelOrigin = new google.maps.Point(12, 30);

        var markerImage = { // https://developers.google.com/maps/documentation/javascript/reference/marker#MarkerLabel
            path: pinSVG,
            anchor: new google.maps.Point(12, 15),
            scaledSize: new google.maps.Size(50, 50),
            fillOpacity: 1,
            fillColor: pinColor,
            strokeWeight: 1,
            strokeColor: "white",
            scale: 1,
            labelOrigin: labelOrigin
        };

        var map;
        var markers = new Array(0);
        var markerIcons = new Array(0);
        var bounds = new google.maps.LatLngBounds();

        // Initialize and add the map
        function initMap() {
            
            const ro = {
                lat: 45.9236768,
                lng: 22.7719346
            };
            
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 4,
                center: ro,
            });


            downloadUrl('http://192.168.1.104/PLS/generateMarkers.php', function(data) {
                var xml = data.responseXML;
                var _markers = xml.documentElement.getElementsByTagName('marker');
                var iconIndex = 0;
                var allPoints = new Array(0);


                Array.prototype.forEach.call(_markers, function(markerElem) {
                    var id = markerElem.getAttribute('id');
                    var name = markerElem.getAttribute('name');

                    var point = new google.maps.LatLng(
                        parseFloat(markerElem.getAttribute('lat')),
                        parseFloat(markerElem.getAttribute('lng')));
                    var dim = parseFloat(markerElem.getAttribute('dim'));
                    var col = markerElem.getAttribute('color');
                    console.log("col : " + col);

                    allPoints.push(point);
                    var infowincontent = document.createElement('div');
                    var strong = document.createElement('strong');

                    strong.textContent = name

                    infowincontent.appendChild(strong);
                    infowincontent.appendChild(document.createElement('br'));

                    var text = document.createElement('text');

                    infowincontent.appendChild(text);
                    var _icon = createIcon(col, dim);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: point,
                        icon: markerIcons[iconIndex],
                        fontweight: "bold",

                        label:{
                            text: name,
                            color: 'white',
                            fontweight: "bold",
                            fontsize: "20px"
                        },
                    });
                    markers.push(marker);
                    bounds.extend(marker.getPosition());
                    console.log("Bounds " + bounds);
                    google.maps.event.addListener(marker, 'click', function() {
                        map.setCenter(marker.getPosition());
                        map.setZoom(10 * dim / 2);
                        //infowindow.setContent(contentStringRdr);
                        infowindow.open(map, marker);
                    });

                    iconIndex++;
                });
                map.fitBounds(bounds);
            });

            
        }


        function createIcon(color, dim) {
            var markerImage = { // https://developers.google.com/maps/documentation/javascript/reference/marker#MarkerLabel
                path: pinSVG,
                anchor: new google.maps.Point(12, 17),
                fillOpacity: 1,
                fillColor: color,
                strokeWeight: 1,
                strokeColor: "white",
                scale: 1,

                labelOrigin: labelOrigin,
            };
            markerIcons.push(markerImage);
            console.log("Icon Added with color : " + color);
        }

        /* Load initialize function */
        google.maps.event.addDomListener(window, 'load', initMap);

        function downloadUrl(url, callback) {
            var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;

            request.onreadystatechange = function() {
                if (request.readyState == 4) {
                    //request.onreadystatechange = doNothing;
                    callback(request, request.status);
                }
            };

            request.open('GET', url, true);
            request.send(null);
        }
    </script>

    <!-- <script type="text/javascript" src="markersLoad.js"></script> -->

    <div id="map" style="width: 90%; height: 90%; margin:auto;"></div>
</body>

</html>