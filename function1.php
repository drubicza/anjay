<?php
function request($url, $token=null, $data=null, $pin=null)
{
    $header[] = "Host: api.gojekapi.com";
    $header[] = "User-Agent: okhttp/3.10.0";
    $header[] = "Accept: application/json";
    $header[] = "Accept-Language: en-ID";
    $header[] = "Content-Type: application/json; charset=UTF-8";
    $header[] = "X-AppVersion: 3.16.1";
    $header[] = "X-UniqueId: 106605982657".mt_rand(1000,9999);
    $header[] = "Connection: keep-alive";
    $header[] = "X-User-Locale: en_ID";
    $header[] = "X-Location: -7.613805,110.633676";
    $header[] = "X-Location-Accuracy: 3.0";

    if ($pin) {
        $header[] = "pin: $pin";
    }

    if ($token) {
        $header[] = "Authorization: Bearer $token";
    }

    $c = curl_init("https://api.gojekapi.com".$url);

    curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);

    if ($data) {
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data);
    }

    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_HEADER, true);
    curl_setopt($c, CURLOPT_HTTPHEADER, $header);

    if ($socks) {
        curl_setopt($c, CURLOPT_HTTPPROXYTUNNEL, true);
        curl_setopt($c, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        curl_setopt($c, CURLOPT_PROXY, $socks);
    }

    $response = curl_exec($c);
    $httpcode = curl_getinfo($c);

    if (!$httpcode) {
        return false;
    } else {
        $header = substr($response, 0, curl_getinfo($c, CURLINFO_HEADER_SIZE));
        $body   = substr($response, curl_getinfo($c, CURLINFO_HEADER_SIZE));
    }

    $json = json_decode($body, true);
    return $json;
}
?>
