<?php
	
$strJsonFileContents = file_get_contents("settings.json");
$settings = json_decode($strJsonFileContents, true);

$url=$argv[1];
$url_result = parse_url($argv[1]);

if ($url=='')
{
  echo "please setup url as\nscript url\n";
  exit;
}


checklogin:

$urlAPI = "https://indexing.googleapis.com/v3/urlNotifications:publish";

$header[0] = "Authorization: Bearer ".$settings["google-key"];
$header[1] = "Content-Type: application/json; charset=UTF-8";

$ch = curl_init( $urlAPI );
$payload = json_encode( array( "url"=> $url, "type" => "URL_UPDATED") );

curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );

curl_setopt( $ch, CURLOPT_HTTPHEADER, $header); 
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch, CURLOPT_POST, 1);


$result = curl_exec($ch);

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


if ($httpcode == "401")
{
	echo "refresh token\n\r";
	$tk = curl_init();
	curl_setopt($tk, CURLOPT_URL, $settings["token-url"]);
	curl_setopt($tk, CURLOPT_POST, 1);
	//curl_setopt($tk, CURLOPT_HTTPHEADER, $header); 
	curl_setopt($tk, CURLOPT_POSTFIELDS, $settings["token-param"]);
	curl_setopt($tk, CURLOPT_COOKIEJAR, "my_cookies.txt");
	curl_setopt($tk, CURLOPT_COOKIEFILE, "my_cookies.txt");
	curl_setopt($tk, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($tk, CURLINFO_HEADER_OUT, true);
	$api_result = curl_exec($tk);
	//echo ($api_result);
	$httpcode = curl_getinfo($tk, CURLINFO_HTTP_CODE);
	if ($httpcode == "200")
	{
		$json=json_decode($api_result);
		$settings["google-key"] = $json->{'access_token'};
		
		$strJsonFileContents = json_encode($settings);
		file_put_contents("settings.json", $strJsonFileContents);
		
		goto checklogin;
	}
	else
	{
		echo "something is wrong!\n\r";
		echo $api_result;
		die;
	}

}

curl_close($ch);

print_r ($result);
		
?>
