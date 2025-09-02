<?php
         
         $data = file_get_contents("https://emo.lv/weather-api/forecast/?city=cesis,latvia");
         $weatherData = json_decode($data, true);
         
    ?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forecast app</title>
    <link rel="stylesheet" href="style.css">
</head>
<body onload="startTime()">
    <div>

    </div>
    <p>
    div -> parent div uh vh -> child    div vnk %
    
    Pilsēta: <?php echo $weatherData['city'] ['name']; ?>
    </p>
    <p>Current Weather</p>
   <p>
    Country : <?php echo $weatherData['country'] ['name']; ?>
    </p>

    <div id="txt"></div>

     <p type="datetime-local"></p>

     <script>
            function startTime() {
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('txt').innerHTML =
            h + ":" + m + ":" + s;
            var t = setTimeout(startTime, 500);
        }
        function checkTime(i) {
            if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
            return i;
        }
    </script>
    
</body>
</html>