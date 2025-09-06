<?php
$city = isset($_GET['city']) ? $_GET['city'] : 'Cesis,latvia';
$data = file_get_contents("https://emo.lv/weather-api/forecast/?city=" . urlencode($city));
$weatherData = json_decode($data, true);

function windDirection($deg) {
  $directions = ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'];
  return $directions[round($deg / 45) % 8];
}

date_default_timezone_set('Europe/Riga');

?>
<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>VTDT Sky Weather App</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body onload="startTime()">

  <nav class="navbar">
    <div class="nav-left">
      <button class="menu-btn"><img src="./gif/menu.png" alt="Menu" /></button>
      <span class="logo">VTDT Sky</span>
      <span class="location"><img src="./gif/location.gif" alt="" /> <?php echo htmlspecialchars($weatherData['city']['name']) . ", " . htmlspecialchars($weatherData['city']['country']); ?></span>
    </div>
    <div class="nav-center">
      <form class="search-bar" method="GET" action="">
        <input type="text" name="city" value="<?php echo htmlspecialchars($city); ?>" />
        <button type="submit"><img src="./gif/worldwide.gif" alt="Globe" /></button>
      </form>
    </div>
    <div class="nav-right">
      <button><img src="./gif/light.png" alt="Light" onclick="toggleDarkMode()" /></button>
      <button><img src="./gif/notification.gif" alt="Notifications" /></button>
      <button><img src="./gif/settings.gif" alt="Settings" /></button>
    </div>
  </nav>

  <div class="container">
    <div class="left">

      <div class="current-weather card">
  <div class="current-weather-flex">
    <div>
      <span class="cw-title">Current Weather</span>
      <div class="cw-row">
        <span class="cw-time">
          <strong>Local time: <span id="txt"></span></strong>
        </span>
        <div class="cw-units">
          <select name="temp_unit" id="temp_unit">
            <option value="C">Celsius and Kilometers</option>
            <option value="F">Fahrenheit and Miles</option>
          </select>
        </div>
      </div>
      <div class="cw-main">
        <img src="./gif/sun.gif" alt="sun" class="nav-icon" />
        <span class="cw-temp"><?php echo round($weatherData['list'][0]['temp']['day'], 1); ?>°C</span>
        <div class="cw-descs">
          <div class="cw-desc"><?php echo ucfirst($weatherData['list'][0]['weather'][0]['description']); ?></div>
          <div class="cw-feels">Feels Like <?php echo round($weatherData['list'][0]['feels_like']['day'], 1); ?>°C</div>
        </div>
      </div>
      <div class="cw-wind">
        Current wind direction: <?php echo windDirection($weatherData['list'][0]['deg']); ?>
      </div>
    </div>
  </div>
</div>


      <div class="stats-grid">
        <div class="card air-quality">
          <img src="./gif/clouds.gif" alt="clouds" class="nav-icon" />
          <div class="label">Air Quality</div>
          <div class="val"><?php echo isset($weatherData['list'][0]['airquality']) ? $weatherData['list'][0]['airquality'] : 2; ?></div>
        </div>
        <div class="card wind">
          <img src="./gif/wind.gif" alt="wind" class="nav-icon" />
          <div class="label">Wind</div>
          <div class="val"><?php echo round($weatherData['list'][0]['speed'], 1); ?> km/h</div>
        </div>
        <div class="card humidity">
          <img src="./gif/humidity.gif" alt="humidity" class="nav-icon" />
          <div class="label">Humidity</div>
          <div class="val"><?php echo $weatherData['list'][0]['humidity']; ?>%</div>
        </div>
        <div class="card visibility">
          <img src="./gif/vision.gif" alt="vision" class="nav-icon" />
          <div class="label">Visibility</div>
          <div class="val"><?php echo isset($weatherData['list'][0]['visibility']) ? round($weatherData['list'][0]['visibility'] / 1000, 1) . " km" : "10 km"; ?></div>
        </div>
        <div class="card pressure-in">
          <img src="./gif/air-pump.gif" alt="Pressure In" class="nav-icon" />
          <div class="label">Pressure</div>
          <div class="val"><?php echo round($weatherData['list'][0]['pressure'] * 0.02953, 2); ?> in</div>
        </div>
        <div class="card pressure-hpa">
          <img src="./gif/air-pump.gif" alt="Pressure hPa" class="nav-icon" />
          <div class="label">Pressure</div>
          <div class="val"><?php echo $weatherData['list'][0]['pressure']; ?> hPa</div>
        </div>
      </div>

      <div class="sun-moon-summary">     
        
      <div class="label">Sunrise & Moon Summary</div>

        <div class="sun-moon-row">
          <div class="summary-cell">
            <img src="./gif/sun.gif" alt="Sun" />
            <div class="label">Air Quality</div>
            <div class="val"><?php echo isset($weatherData['list'][0]['airquality']) ? $weatherData['list'][0]['airquality'] : 2; ?></div>
          </div>
          <div class="summary-cell">
            <img src="./gif/field.gif" alt="Sunrise" />
            <div class="label">Sunrise</div>
            <div class="val"><?php echo date('h:i A', $weatherData['list'][0]['sunrise']); ?></div>
          </div>
          <div class="summary-cell progress-cell">
            <div class="progress-arc sun-arc"></div>
          </div>
          <div class="summary-cell">
            <img src="./gif/sunset.gif" alt="Sunset" />
            <div class="label">Sunset</div>
            <div class="val"><?php echo date('h:i A', $weatherData['list'][0]['sunset']); ?></div>
          </div>
        </div>
        <div class="sun-moon-row">
          <div class="summary-cell">
            <img src="./gif/moon.gif" alt="Moon" />
            <div class="label">Air Quality</div>
            <div class="val"><?php echo isset($weatherData['list'][0]['airquality']) ? $weatherData['list'][0]['airquality'] : 2; ?></div>
          </div>
          <div class="summary-cell">
            <img src="./gif/moon-rise.gif" alt="Moonrise" />
            <div class="label">Moonrise</div>
            <div class="val"><?php echo date('h:i A', $weatherData['list'][0]['moonrise'] ?? strtotime('19:51')); ?></div>
          </div>
          <div class="summary-cell progress-cell">
            <div class="progress-arc moon-arc"></div>
          </div>
          <div class="summary-cell">
            <img src="./gif/moon-set.gif" alt="Moonset" />
            <div class="label">Moonset</div>
            <div class="val"><?php echo date('h:i A', $weatherData['list'][0]['moonset'] ?? strtotime('04:03')); ?></div>
          </div>
        </div>
      </div>

    </div>

    <div class="right-column">
      <div class="big-card forecast-block">
        <div class="forecast-tabs">
          <button class="tab active" onclick="showToday()">Today</button>
          <button class="tab" onclick="showTomorrow()">Tomorrow</button>
          <button class="tab" onclick="show10Days()">10 Days</button>
        </div>
        <div class="forecast-content">
          <div id="forecast-today">
            <?php foreach ($weatherData["list"] as $index => $forecast): ?>
              <?php if ($index === 0): ?>
                <!-- Šodien stundas prognoze, ja ir hourly -->
                <?php if (isset($forecast['hourly'])): ?>
                  <?php foreach ($forecast['hourly'] as $hour): ?>
                    <div class="hour-row">
                      <span><?php echo date("h:i A", $hour["dt"]); ?></span>
                      <span><?php echo ucfirst($hour['weather'][0]['description']); ?></span>
                      <span><?php echo round($hour["temp"], 1); ?>°C</span>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p>Hourly data for today not available.</p>
                <?php endif; ?>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>

          <div id="forecast-tomorrow" style="display:none">
            <?php foreach ($weatherData["list"] as $index => $forecast): ?>
              <?php if ($index === 1): ?>
                <?php if (isset($forecast['hourly'])): ?>
                  <?php foreach ($forecast['hourly'] as $hour): ?>
                    <div class="hour-row">
                      <span><?php echo date("h:i A", $hour["dt"]); ?></span>
                      <span><?php echo ucfirst($hour['weather'][0]['description']); ?></span>
                      <span><?php echo round($hour["temp"], 1); ?>°C</span>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <p>Hourly data for tomorrow not available.</p>
                <?php endif; ?>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>

          <div id="forecast-10days" style="display:none">
            <?php foreach ($weatherData["list"] as $day): ?>
              <div class="day-row">
                <span><?php echo date("d.m.Y", $day["dt"]); ?></span>
                <span><?php echo ucfirst($day["weather"][0]["main"]); ?></span>
                <span><?php echo round($day["temp"]["day"], 1); ?>°C</span>
              </div>
            <?php endforeach; ?>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script>
    function showToday() {
      document.getElementById("forecast-today").style.display = "block";
      document.getElementById("forecast-tomorrow").style.display = "none";
      document.getElementById("forecast-10days").style.display = "none";
      setActiveTab('Today');
    }
    function showTomorrow() {
      document.getElementById("forecast-today").style.display = "none";
      document.getElementById("forecast-tomorrow").style.display = "block";
      document.getElementById("forecast-10days").style.display = "none";
      setActiveTab('Tomorrow');
    }
    function show10Days() {
      document.getElementById("forecast-today").style.display = "none";
      document.getElementById("forecast-tomorrow").style.display = "none";
      document.getElementById("forecast-10days").style.display = "block";
      setActiveTab('10Days');
    }
    function setActiveTab(tabName) {
      const tabs = document.querySelectorAll('.tab');
      tabs.forEach(tab => {
        tab.classList.remove('active');
        if (tab.textContent.trim() === tabName) tab.classList.add('active');
      });
    }
    function startTime() {
      var today = new Date();
      var h = today.getHours();
      var m = today.getMinutes();
      var s = today.getSeconds();
      var ampm = h >= 12 ? 'PM' : 'AM';
      h = h % 12;
      h = h ? h : 12;
      m = checkTime(m);
      s = checkTime(s);
      document.getElementById('txt').innerHTML =
        h + ":" + m + ":" + s + " " + ampm;
      setTimeout(startTime, 500);
    }
    function checkTime(i) {
      if (i < 10) { i = "0" + i; }
      return i;
    }
    function toggleDarkMode() {
      document.body.classList.toggle("dark-mode");
    }
  </script>

</body>
</html>
