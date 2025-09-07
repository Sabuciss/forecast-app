document.addEventListener("DOMContentLoaded", function() {
  const buttons = document.querySelectorAll(".buttons button");
  const forecastContent = document.getElementById("forecast-content");
  const darkModeBtn = document.querySelector(".darkmode");
  const themeIcon = darkModeBtn.querySelector("img");
  const themeText = darkModeBtn.querySelector(".mode-text");
  const unitSelect = document.getElementById("unit-select");

  function cToF(c) { return (c * 9 / 5 + 32).toFixed(1); }
  function kmhToMph(kmh) { return (kmh / 1.609).toFixed(1); }

  function updateCurrentWeatherUnits() {
    const main = document.getElementById("mainTemp");
    const feels = document.getElementById("feelsLike");
    const wind = document.getElementById("windValue");

    const c = parseFloat(main.getAttribute("data-c"));
    const feelsVal = parseFloat(feels.getAttribute("data-c"));
    const kmh = parseFloat(wind.getAttribute("data-kmh"));

    if(unitSelect.value === "FM") {
      main.textContent = `${cToF(c)}°F`;
      feels.textContent = `Feels Like ${cToF(feelsVal)}°F`;
      wind.textContent = `${kmhToMph(kmh)} mph`;
    } else {
      main.textContent = `${c.toFixed(1)}°C`;
      feels.textContent = `Feels Like ${feelsVal.toFixed(1)}°C`;
      wind.textContent = `${kmh.toFixed(1)} km/h`;
    }
  }

  function updateForecastUnits() {
    document.querySelectorAll(".forecast-content .forecast-row").forEach(row => {
      const tempElement = row.querySelector(".forecast-temp");
      if(!tempElement) return;

      const c = parseFloat(tempElement.getAttribute("data-c"));
      const feels = parseFloat(tempElement.getAttribute("data-feels"));
      const cDay = parseFloat(tempElement.getAttribute("data-c-day"));
      const wind = parseFloat(tempElement.getAttribute("data-wind"));
      const humidity = tempElement.getAttribute("data-humidity");

      const tempValEl = tempElement.querySelector(".temp");
      const detailsEl = tempElement.querySelector(".details");

      if(!isNaN(c) && !isNaN(feels)){
        if(unitSelect.value === "FM"){
          tempValEl.textContent = `${cToF(c)}°F`;
          if(detailsEl) detailsEl.textContent = `Feels Like ${cToF(feels)}°F`;
        } else {
          tempValEl.textContent = `${c.toFixed(1)}°C`;
          if(detailsEl) detailsEl.textContent = `Feels Like ${feels.toFixed(1)}°C`;
        }
      } else if(!isNaN(cDay)) {
        if(unitSelect.value === "FM"){
          tempValEl.textContent = `${cToF(cDay)}°F`;
          if(detailsEl) detailsEl.innerHTML = `Wind: ${kmhToMph(wind)} mph<br>Humidity: ${humidity}%`;
        } else {
          tempValEl.textContent = `${cDay.toFixed(1)}°C`;
          if(detailsEl) detailsEl.innerHTML = `Wind: ${wind.toFixed(1)} km/h<br>Humidity: ${humidity}%`;
        }
      }
    });
    document.querySelectorAll(".wind-speed").forEach(el =>{
      const kmh = parseFloat(el.getAttribute("data-kmh"));
      if(!isNaN(kmh)){
        if(unitSelect.value === "FM"){
          el.textContent = `${kmhToMph(kmh)} mph`;
        } else {
          el.textContent = `${kmh.toFixed(1)} km/h`;
        }
      }
    });
  }


  
  function renderDay(index) {
    const day = weatherData.list[index];
    const parts = [
      {label: "Rīts", temp: day.temp.morn, feels: day.feels_like.morn},
      {label: "Diena", temp: day.temp.day, feels: day.feels_like.day},
      {label: "Vakars", temp: day.temp.eve, feels: day.feels_like.eve},
      {label: "Nakts", temp: day.temp.night, feels: day.feels_like.night}
    ];
    let html = '<div class="forecast-content">';
    parts.forEach(p => {
      html += `<div class="forecast-row">
                  <div class="forecast-time">${p.label}</div>
                  <div class="forecast-temp" data-c="${p.temp}" data-feels="${p.feels}">
                    <div class="temp">${p.temp.toFixed(1)}°C</div>
                    <div class="details">${p.feels.toFixed(1)}°C</div>
                  </div>
                </div>`;
    });
    html += '</div>';
    return html;
  }

  function renderTenDays() {
    let html = '<div class="forecast-content">';
    weatherData.list.slice(0, 10).forEach(day => {
      const dateObj = new Date(day.dt * 1000);
      const dayName = dateObj.toLocaleDateString('lv-LV', {weekday: 'long'});
      const dateStr = dateObj.toISOString().slice(0, 10);
      html += `<div class="forecast-row">
                  <div>${dayName}</div>
                  <div>${dateStr}</div>
                  <div class="forecast-temp" data-c-day="${day.temp.day}" data-wind="${(day.speed * 3.6).toFixed(1)}" data-humidity="${day.humidity}">
                    <div class="temp">${day.temp.day.toFixed(1)}°C</div>
                    <div class="details">Wind: ${(day.speed * 3.6).toFixed(1)} km/h<br>Humidity: ${day.humidity}%</div>
                  </div>
                </div>`;
    });
    html += '</div>';
    return html;
  }

  function loadForecast(type) {
    if(type === "today") forecastContent.innerHTML = renderDay(0);
    else if(type === "tomorrow") forecastContent.innerHTML = renderDay(1);
    else if(type === "ten_days") forecastContent.innerHTML = renderTenDays();

    updateForecastUnits();
  }

  buttons.forEach(button => {
    button.addEventListener("click", () => {
      buttons.forEach(btn => btn.classList.remove("active"));
      button.classList.add("active");
      if(button.classList.contains("today")) loadForecast("today");
      else if(button.classList.contains("tomorrow")) loadForecast("tomorrow");
      else if(button.classList.contains("ten_days")) loadForecast("ten_days");
    });
  });

  if(buttons.length) buttons[0].click();

  unitSelect.addEventListener("change", () => {
    updateCurrentWeatherUnits();
    updateForecastUnits();
  });

  darkModeBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    if(document.body.classList.contains("dark-mode")){
      themeIcon.src = "gif/dark.png";
      themeText.textContent = "Dark";
    } else {
      themeIcon.src = "gif/light.png";
      themeText.textContent = "Light";
    }
  });

  function start() {
    function checkTime(i) {
      return i < 10 ? "0" + i : i;
    }
    function updateClock() {
      const now = new Date();
      let h = now.getHours();
      const m = checkTime(now.getMinutes());
      const s = checkTime(now.getSeconds());
      const ampm = h >= 12 ? "PM" : "AM";
      h = h % 12 || 12;
      document.getElementById("txt").textContent = `${h}:${m}:${s} ${ampm}`;
      setTimeout(updateClock, 500);
    }
    updateClock();
  }
  start();
});
