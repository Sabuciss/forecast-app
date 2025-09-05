<?php
         
         $data = file_get_contents("https://emo.lv/weather-api/forecast/?city=cesis,latvia");
         $weatherData = json_decode($data, true);

         $today = $weatherData['list'][0];

         
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

  <div class="container">
  <nav class="navbar">
    <div class="logo">VTDT Sky</div>
    <form class="search" method="GET" action="">
      <input type="text" name="city" placeholder="Ievadi pilsētu" value="<?php echo htmlspecialchars($_GET['city'] ?? 'Cesis,latvia'); ?>" />
      <button type="submit">Meklēt</button>
    </form>
  </nav>

  <div class="current-weather">
    <div class="card-title">Current Weather</div>
    <p>Local time: <span id="txt"></span></p>
    <div class="card-value"><?php echo round($weatherData['list'][0]['temp']['day'], 1); ?>°C</div>
    <div><?php echo ucfirst($weatherData['list'][0]['weather'][0]['description']); ?></div>
  </div>

  <div class="air-quality">
    Air Quality: 2
  </div>
  <div class="wind">
    Wind: <?php echo round($weatherData['list'][0]['speed'], 2); ?> km/h
  </div>
  <div class="humidity">
    Humidity: <?php echo $weatherData['list'][0]['humidity']; ?>%
  </div>
  <div class="visibility">
    Visibility:  <?php echo $weatherData['list'][0]['visibility']; ?>
  </div>
  <div class="pressure-in">
    Pressure: <?php 
      $pressure_hpa = $weatherData['list'][0]['pressure'];
      $pressure_in = round($pressure_hpa * 0.02953, 2);
      echo $pressure_in . " in";
    ?>
  </div>
  <div class="pressure">
    Pressure: <?php echo $weatherData['list'][0]['pressure']; ?>°
  </div>

  <div class="sun-moon">
    <p>Sun & Moon Summary</p>
    <p>Sunrise <?php echo date('g:i A', $weatherData['list'][0]['sunrise']); ?></p>
    <p>Sunset <?php echo date('g:i A', $weatherData['list'][0]['sunset']); ?></p>
  </div>
    <div class="right">
      <div class="forecast-header">
        <span>Today</span>
        <span>Tomorrow</span>
        <span class="active">10 Days</span>
      </div>
      
    </div>
  </div>



     <p type="datetime-local"></p>

    <script>
    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        var ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12;
        h = h ? h : 12; // ja h ir 0, tad ieliek 12
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('txt').innerHTML =
            h + ":" + m + ":" + s + " " + ampm;
        setTimeout(startTime, 500);
    }
    function checkTime(i) {
        if (i < 10) {i = "0" + i};  
        return i;
    }


</script>
    
</body>
</html>
