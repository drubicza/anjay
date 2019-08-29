<?php

function fn_curl($p_url, $p_post=0)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $p_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    if ($p_post) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $p_post);
    }
    $c_res = curl_exec($ch);
    return $c_res;
}

echo "Gopay Sender BOT - Coded By RaP\n";
echo "Thanks To Nasrullah M. Haris\n\n";
echo "Use (62) for Indonesia Number\n";
echo "Use (1) for US Number\n\n";
echo "Enter Phone Number : ";
$s_phone  = trim(fgets(STDIN));
$s_result = fn_curl("http://156.67.214.4/gopay/index.php","phone=".$s_phone."&submit=");

if (preg_match("/selamat makan!/i", $s_result)) {
    echo "[+] Sukses kirim gopay, selamat makan!\n";
} else {
    echo "[+] Gagal kirim mampus lo\n";
}
?>
