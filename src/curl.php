<?php

$curl = curl_init();
$request = '{
    "name":"generateToken",
    "param":{
        "email":"admin@gmail.com",
        "pass":"admin123"
    }
}';
curl_setopt($curl, CURLOPT_URL, 'localhost:80');
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, ['content-type:application/json']);
curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$result=curl_exec($curl);

$err = curl_error($curl);

if($err){
    echo 'Curl Error : '. $err;
}else {
    header('content-type: application/json');
    print_r($result);
}
?>