<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        #showc{
            color: red;
        }
        #showf{
            color: blue;
        }
    </style>
  <title>Weather</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php
require_once('geoplugin.class.php');
$geoplugin = new geoPlugin();
// If we wanted to change the base currency, we would uncomment the following line
// $geoplugin->currency = 'EUR';
 
$geoplugin->locate();
 
//echo "Geolocation results for {$geoplugin->ip}: <br />\n".
//	"City: {$geoplugin->city} <br />\n".
//	"Region: {$geoplugin->region} <br />\n".
//	"Region Code: {$geoplugin->regionCode} <br />\n".
//	"Region Name: {$geoplugin->regionName} <br />\n".
//	"DMA Code: {$geoplugin->dmaCode} <br />\n".
//	"Country Name: {$geoplugin->countryName} <br />\n".
//	"Country Code: {$geoplugin->countryCode} <br />\n".
//	"In the EU?: {$geoplugin->inEU} <br />\n".
//	"EU VAT Rate: {$geoplugin->euVATrate} <br />\n".
//	"Latitude: {$geoplugin->latitude} <br />\n".
//	"Longitude: {$geoplugin->longitude} <br />\n".
//	"Radius of Accuracy (Miles): {$geoplugin->locationAccuracyRadius} <br />\n".
//	"Timezone: {$geoplugin->timezone}  <br />\n".
//	"Currency Code: {$geoplugin->currencyCode} <br />\n".
//	"Currency Symbol: {$geoplugin->currencySymbol} <br />\n".
//	"Exchange Rate: {$geoplugin->currencyConverter} <br />\n";
 
$lat = $geoplugin->latitude;
$long = $geoplugin->longitude;

$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://weatherapi-com.p.rapidapi.com/current.json?q=$lat%2C%20$long",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		
	],
]);

$response = curl_exec($curl);

$decoded_json = json_decode($response, true);
//get city
$locationname = $decoded_json['location']['name'];
$locationregion = $decoded_json['location']['region'];
$locationcountry= $decoded_json['location']['country'];

// header section location
echo '<header><h1>';
echo $locationname;
echo ', <span class="locationregion">';
echo $locationregion;
echo '</span>';
echo ' <span class="locationcountry">';
echo $locationcountry;
echo '</span>';
echo '</h1></header>';

// main section current weather
$current= $decoded_json['current']['temp_c'];
$currentf= $decoded_json['current']['temp_f'];
$feelslike= $decoded_json['current']['feelslike_c'];
$feelslikef= $decoded_json['current']['feelslike_f'];
$currentcondition= $decoded_json['current']['condition']['text'];
$currenticon= $decoded_json['current']['condition']['icon'];
$lastupdated = $decoded_json['current']['last_updated'];

echo '<main>';
echo '<div class="currenttemp"><span id="showc">';
echo $current;
echo '<sup>o</sup> C</span><span id="showf">';
echo $currentf;
echo '<sup>o</sup> F</span>';
echo '<span class="feelslike"> Feels like '; 
echo '<span id="showc"> ';
echo $feelslike;
echo '<sup>o</sup> C</span><span id="showf"> ';
echo $feelslikef;
echo '<sup>o</sup> F</span>';
echo '</span></div>'; // end currenttemp
echo '<div class="currentcondition">';
echo '<img src="'. $currenticon .'">';
echo '<span class="condition">';
echo $currentcondition;
echo '</span>';
echo '<span class="lastupdated">';
echo 'Last updated';
echo $lastupdated;
echo '</span>';
echo '</div>'; // current condition

// stats 
echo '<div class="row" id="stats">';
    echo '<div class="col-sm-4">';
    
    echo '</div>';
        echo '<div class="col-sm-4">';
    
    echo '</div>';
        echo '<div class="col-sm-4">';
    
    echo '</div>';
echo '</div>'; // stats
echo '</main>';

$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo $response;
}
 
?>

<footer>
  <p>  Location found using <a href="https://www.geoplugin.com/webservices/php">GeoPlugin</a>.</p>
  <p>
      <a href="https://www.weatherapi.com/" title="Free Weather API"><img src='//cdn.weatherapi.com/v4/images/weatherapi_logo.png' alt="Weather data by WeatherAPI.com" border="0"></a>
  </p>
</footer>
</body>
</html>
