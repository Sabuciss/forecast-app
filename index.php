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
</head>
<body>
     <p> 
        <?php echo "Pilsēta: " . $weatherData['location']['city'];?>

    </p>


    
</body>
</html>