<?php
include_once __DIR__.'/libs/simple_html_dom.php';
$username = '';
$password = '';
$ch = curl_init();
curl_setopt_array($ch, array(
	CURLOPT_URL => "https://thesieure.com/account/login",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_COOKIEFILE => __DIR__."/session.txt",
	CURLOPT_COOKIEJAR => __DIR__."/session.txt"
));
$exec = curl_exec($ch);
curl_close($ch);
if (strpos($exec, "<h4>Đăng nhập tài khoản</h4>") !== false)
{
    $TOKEN_CSRF = str_get_html($exec)->find("input[name=_token]", 0)->value;
    // Get Cookie
    $curl = curl_init();
    curl_setopt_array($curl, array(
    	CURLOPT_URL => "https://thesieure.com/account/login",
    	CURLOPT_COOKIEJAR => __DIR__."/session.txt",
    	CURLOPT_COOKIEFILE => __DIR__."/session.txt",
    	CURLOPT_CONNECTTIMEOUT => 30,
    	CURLOPT_RETURNTRANSFER => true,
    	CURLOPT_SSL_VERIFYPEER => false,
    	CURLOPT_FOLLOWLOCATION => 1,
    	CURLOPT_POST => 1,
    	CURLOPT_POSTFIELDS => "phoneOrEmail=".$username."&password=".$password."&_token=".$TOKEN_CSRF."&g-recaptcha-response=true&remember=checked",
    	CURLINFO_HEADER_OUT => true
    ));
    $exec = curl_exec($curl);
    curl_close($curl);
    if (strpos($exec, "<h4>Đăng nhập tài khoản</h4>") !== false) 
    {
        unlink(__DIR__."/session.txt");
        die(json_encode(array('status' => false, 'message' => str_get_html($exec)->find('div .error-messages ul li', 0)->plaintext)));
    }
}
$ch = curl_init();
curl_setopt_array($ch, array(
	CURLOPT_URL => "https://thesieure.com/wallet/transfer",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_COOKIEFILE => __DIR__."/session.txt",
	CURLOPT_COOKIEJAR => __DIR__."/session.txt"
));
$exec = curl_exec($ch);
curl_close($ch);
$rs = str_get_html($exec);
$lol =  $rs->find('tbody', 2);
foreach($lol->find('tr') as $data)
{
    $tuan[] = array(
        'trading_code' => $data->find('td', 0)->plaintext,
		'money_cost' => $data->find('td', 1)->plaintext,
		'username_send_or_receive' => $data->find('td', 2)->plaintext,
		'status' => $data->find('td', 4)->plaintext,
		'content_send' => $data->find('td', 5)->plaintext,
		'time_created' => $data->find('td', 3)->plaintext
    );
}
die(json_encode($tuan));