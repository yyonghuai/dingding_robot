<?php  
function request_by_curl($remote_server, $post_string) {  
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL, $remote_server);
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
    // 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
    // curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); 
    // curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $data = curl_exec($ch);
    curl_close($ch);                
    return $data;  
}
$webhook = getWebHook();
$message="我就是我, 是不一样的烟火";
$data = array ('msgtype' => 'text','text' => array ('content' => $message),"at"=>["atMobiles"=>["130****0000"],"isAtAll"=> false]);
$data_string = json_encode($data);

$result = request_by_curl($webhook, $data_string);  
function getWebHook(){
    list($s1, $s2) = explode(' ', microtime());

    $timestamp = (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);

    $secret = 'xxxx';

    $data = $timestamp . "\n" . $secret;

    $signStr = base64_encode(hash_hmac('sha256', $data, $secret,true));

    $signStr = utf8_encode(urlencode($signStr));

    $webhook = "https://oapi.dingtalk.com/robot/send?access_token=xxxx";

    $webhook.= "&timestamp=$timestamp&sign=$signStr";
    return $webhook;
}
