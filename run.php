<?php
ulang:
$uuid = gen_uuid();
$name = nama();
$pecah = explode(" ",$name);
$first = $pecah[0];
$last = $pecah[1];
$domain = "luffem.com";
$mail = strtolower(str_replace(" ", "", $name).mt_rand(10, 9999));
$email = $mail."@".$domain;


$regis = regis($email, $uuid);
echo "$regis\n";
do{
  echo color($color = "blue" , "Getting Verify Email...");
  $getmail = get_mail($domain, $mail);
  $check = get_between($getmail, '<h1 style="font-size:1.1rem;font-weight:100;padding:0;margin:0;line-height:0;display:inherit;">', '</h1>');

  if(preg_match('/Welcome to BABB!/i', $check)){
    $otp = get_between($getmail, '<center> <h2> <b> ', ' </b> </h2></center>');
    echo color($color = "green" , "Success $otp!\n");
    $success = 1;
  }else{
    echo "Failed\n";
    $success = 0;
  }
}while($success==0);
$confirm = confirm($email, $otp);
$check = "{".$confirm."}";
echo "$check\n";
$token = get_between($check, '{"', '"}');
$getcode = getcode($token);
echo "$getcode\n";
echo "[?] Code: ";
$code = trim(fgets(STDIN));
$verify = verify($token, $code);
echo "$verify\n";
$profile = profile_save($first, $token);
echo "$profile\n\n";
goto ulang;


function regis($email, $uuid){
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, 'https://api.babbapp.com/v1.0/auth/signup');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, '{"email": "'.$email.'", "password": "Anjay123@", "confirmPassword": "Anjay123@", "refCode": "188290", "installationId": "'.$uuid.'"}');

  $headers = array();
  $headers[] = 'Accept: application/json';
  $headers[] = 'Content-Type: application/json-patch+json';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);
  return $result;
}

function confirm($email, $otp){
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, 'https://api.babbapp.com/v1.0/auth/signup/confirm?email='.$email.'&code='.$otp.'');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);

  $headers = array();
  $headers[] = 'Accept: application/json';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);
  return $result;
}

function getcode($token){
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, 'https://api.babbapp.com/v1.0/auth/phone/code?phoneNumber=%2B19093431654');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

  $headers = array();
  $headers[] = 'User-Agent: LGE LGM-V300K 540x960 Android 5.1.1';
  $headers[] = 'Host: api.babbapp.com';
  $headers[] = 'Authorization: Bearer '.$token.'';
  $headers[] = 'Content-Type: application/x-www-form-urlencoded';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);
  return $result;
}

function verify($token, $code){
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, 'https://api.babbapp.com/v1.0/auth/phone/verify?code='.$code.'');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);

  $headers = array();
  $headers[] = 'User-Agent: LGE LGM-V300K 540x960 Android 5.1.1';
  $headers[] = 'Host: api.babbapp.com';
  $headers[] = 'Authorization: Bearer '.$token.'';
  $headers[] = 'Content-Type: application/x-www-form-urlencoded';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);
  return $result;
}

function profile_save($first, $token){
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, 'https://api.babbapp.com/v1.0/user/profile/save');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, '{"city":"Jakarta","country":"INDONESIA","firstName":"'.$first.'","houseNumber":"0","isAnonymous":false,"isFilled":false,"lastName":"Kompi","phoneNumber":"+19093431654","postcode":"12368","street":"Kalibata","verificationStatus":"NotVerified"}');

  $headers = array();
  $headers[] = 'User-Agent: LGE LGM-V300K 540x960 Android 5.1.1';
  $headers[] = 'Host: api.babbapp.com';
  $headers[] = 'Authorization: Bearer '.$token.'';
  $headers[] = 'Content-Type: application/json-patch+json';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);
  return $result;
}

function nama(){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://ninjaname.horseridersupply.com/indonesian_name.php");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$ex = curl_exec($ch);
	// $rand = json_decode($rnd_get, true);
	preg_match_all('~(&bull; (.*?)<br/>&bull; )~', $ex, $name);
	return $name[2][mt_rand(0, 14) ];
}

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function get_mail($domain, $email){
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://generator.email/$domain/$email",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(
      "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3",
      "accept-encoding: gzip, deflate, br",
      "upgrade-insecure-requests: 1",
      "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36",
      "cookie: _ga=GA1.2.659238676.1567004853; _gid=GA1.2.273162863.1569757277; embx=%5B%22$email%40$domain%22%2C%22hcycl%40nongzaa.tk%22%5D; _gat=1; io=io=tIcarRGNgwqgtn40OGr4; surl=$domain%2F$email",
      "Content-Type: application/json"
    ),
  ));

  $result = curl_exec($curl);
  return $result;
}

function color($color = "default" , $text) {
  $arrayColor = array(
    'grey' 		=> '1;30',
    'red' 		=> '1;31',
    'green' 	=> '1;32',
    'yellow' 	=> '1;33',
    'blue' 		=> '1;34',
    'purple' 	=> '1;35',
    'nevy' 		=> '1;36',
    'white' 	=> '1;0',
  );
  return "\033[".$arrayColor[$color]."m".$text."\033[0m";
}

function get_between($string, $start, $end){
   $string = " ".$string;
   $ini = strpos($string,$start);
   if ($ini == 0) return "";
   $ini += strlen($start);
   $len = strpos($string,$end,$ini) - $ini;
   return substr($string,$ini,$len);
}
?>
