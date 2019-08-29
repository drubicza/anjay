<?php
include("function1.php");

function nama()
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://ninjaname.horseridersupply.com/indonesian_name.php");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $ex = curl_exec($ch);
    preg_match_all("~(&bull; (.*?)<br/>&bull; )~",$ex,$name);
    return $name[2][mt_rand(0,14)];
}

function register($no)
{
    $nama     = nama();
    $email    = str_replace(" ","",$nama).mt_rand(100,999);
    $data     = '{"name":"'.nama().'","email":"'.$email.'@gmail.com","phone":"+'.$no.'","signed_up_country":"ID"}';
    $register = request("/v5/customers","",$data);

    if ($register["success"] == 1) {
        return $register["data"]["otp_token"];
    } else {
        return false;
    }
}

function verif($otp,$token)
{
    $data  = '{"client_name":"gojek:cons:android","data":{"otp":"'.$otp.'","otp_token":"'.$token.'"},"client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e"}';
    $verif = request("/v5/customers/phone/verify","",$data);

    if ($verif["success"] == 1) {
        return $verif["data"]["access_token"];
    } else {
        return false;
    }
}

function login($no)
{
    $nama     = nama();
    $email    = str_replace(" ","",$nama).mt_rand(100,999);
    $data     = '{"phone":"+'.$no.'"}';
    $register = request("/v4/customers/login_with_phone","",$data);

    print_r($register);

    if ($register["success"] == 1) {
        return $register["data"]["login_token"];
    } else {
        return false;
    }
}

function veriflogin($otp,$token)
{
    $data  = '{"client_name":"gojek:cons:android","client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e","data":{"otp":"'.$otp.'","otp_token":"'.$token.'"},"grant_type":"otp","scopes":"gojek:customer:transaction gojek:customer:readonly"}';
    $verif = request("/v4/customers/login/verify","",$data);

    if ($verif["success"] == 1) {
        return $verif["data"]["access_token"];
    } else {
        return false;
    }
}

function claim($token)
{
    $data  = '{"promo_code":"GOFOODNASGOR07"}';
    $claim = request("/go-promotions/v1/promotions/enrollments",$token,$data);

    if ($claim["success"] == 1) {
        return $claim["data"]["message"];
    } else {
        return false;
    }
}

echo "Choose Login or Register? Login = 1 & Register = 2: ";
$type = trim(fgets(STDIN));

if ($type == 2) {
    echo "Silahkan Register\n";
    echo "Input 62 For ID and 1 For US Phone Number\n";
    echo "Enter Number: ";
    $nope     = trim(fgets(STDIN));
    $register = register($nope);

    if ($register == false) {
        echo "Gagal Mendapatkan OTP, Pake No Yg belum di regist!\n";
    } else {
        echo "Enter Your OTP: ";
        $otp   = trim(fgets(STDIN));
        $verif = verif($otp,$register);

        if ($verif == false) {
            echo "Gagal Daftar Pak\n";
        } else {
            echo "Siap Untuk Claim\n";
            $claim = claim($verif);

            if ($claim == false) {
                echo "Gagal Claim Voucher, Silahkam claim manual\n";
            } else {
                echo $claim."\n";
            }
        }
    }
} else if ($type == 1) {
    echo "Silahkan Login\n";
    echo "Input 62 For ID and 1 For US Phone Number\n";
    echo "Enter Number: ";
    $nope  = trim(fgets(STDIN));
    $login = login($nope);

    if ($login == false) {
        echo "Gagal mendapatkan OTP\n";
    } else {
        echo "Enter Your OTP: ";
        $otp   = trim(fgets(STDIN));
        $verif = veriflogin($otp,$login);

        if ($verif == false) {
            echo "Gagal Login Pak\n";
        } else {
            echo "Siap untuk Claim\n";
            $claim = claim($verif);

            if ($claim == false) {
                echo "Gagal Claim Pak, Silahkan claim Manual!\n";
            } else {
                echo $claim."\n";
            }
        }
    }
}
?>
