<?
	
$strJsonFileContents = file_get_contents("settings.json");
$settings = json_decode($strJsonFileContents, true);
//var_dump($settings);	

$url=$argv[1];
$url_result = parse_url($argv[1]);

if ($url=='')
{
  echo "please setup url as\nscript url\n";
  exit;
}

$postFields["siteUrl"] = $url_result['scheme']."://".$url_result['host'];
$postFields["url"]=$url;

//var_dump($postFields);
$header_json[0] = "Content-Type: application/json; charset=utf-8";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://ssl.bing.com/webmaster/api.svc/json/GetUrlSubmissionQuota?apikey='.$settings['bing-key'].'&siteURL='.$postFields["siteUrl"]);
curl_setopt($ch, CURLOPT_POST, 0);
//curl_setopt($ch, CURLOPT_POSTFIELDS,(json_encode($postFields)));
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header_json); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
$resultpage = curl_exec($ch);

echo $resultpage;
	
?>