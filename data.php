<?php
function curl_get($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    
    curl_close($ch);
    return $data;
}
$resp = curl_get("https://test.tuanori.me/");
$obj = json_decode($resp, true);
foreach($obj as $data)
{
    $sotien = $data['money_cost'];
    $cmt    = $data['content_send'];
    $magd   = $data['trading_code'];
    $user   = $data['username_send_or_receive'];
    echo "Bạn vừa chuyển/nhận $sotien từ $user với mã gd: $magd <br/>";
}