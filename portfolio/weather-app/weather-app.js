var userLon = "";
var userLat = "";
var apiLink = "https://api.openweathermap.org/data/2.5/weather?id=524901&APPID=6493ecfdc8243d318bd51c9b3c86d235&";
var defaultUnit = "metric";

$(document).ready(function() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      userLat = position.coords.latitude;
      userLon = position.coords.longitude;
      getWeather(userLat, userLon, defaultUnit);
      getMap(userLat, userLon);
    });
  } else {
    console.log("Geolocation not supported");
  }

  function getWeather(lat, lon, unit) {
    var url = apiLink+"lat="+lat+"&lon="+lon+"&units="+unit;
    $.getJSON(url, function(localWeather){
      var city = localWeather.name;
      var country = localWeather.sys.country;
      $("#weather-location").html(city+", "+country);
      if ($("#weather-unit-switch").is(":checked")) {
        var temp = localWeather.main.temp+"<span class='weather-unit'>&deg;F</span>";
        $("#weather-current-temp").html(temp);
      } else {
        var temp = localWeather.main.temp+"<span class='weather-unit'>&deg;C</span>";
        $("#weather-current-temp").html(temp);
      }
      var iconID = localWeather.weather[0].id;
      $("#weather-icon").html("<i class='owf owf-"+iconID+" owf-5x'></i>");
      var description = localWeather.weather[0].main;
      $("#weather-description").html(description);
    });
  }

  //Courtesy of http://www.w3resource.com/javascript-exercises/javascript-string-exercise-9.php
  function capitaliseString(str) {
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
  }

  function unitSwitch() {
    $('#weather-unit-switch').on('click', function() {
      if ($("#weather-unit-switch").is(":checked")) {
        getWeather(userLat, userLon, "imperial");
      } else {
        getWeather(userLat, userLon, "metric");
      };
    });
  }
  unitSwitch();

  function getMap(lat, lon) {
    var mapOptions = {
      zoom: 14,
      center: new google.maps.LatLng(lat, lon),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      gestureHandling: 'greedy',
      scrollwheel: false
    }
    var map = new google.maps.Map(document.getElementById("weather-map-background"), mapOptions);
    var marker = new google.maps.Marker({
      position: new google.maps.LatLng(lat, lon),
      map: map,
      title: 'You Are Here'
    });
    marker.setMap(map);
  }

});
