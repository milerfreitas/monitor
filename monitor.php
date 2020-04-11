<?php
$adm = array('name' => 'Nome Sobrenome',
	     'phone' => '(00) 91234-5678');

$conn = new mysqli('localhost', 'root', 'passwd', 'monitor');
$conn->set_charset('utf8');
$sql = "SELECT `server`, GROUP_CONCAT(`service` SEPARATOR ', ') AS `service`, GROUP_CONCAT(`port` SEPARATOR ', ') AS `port` FROM `server`, `server_data` WHERE `server_data`.`server_id` = `server`.`id` GROUP BY `server`;";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
	$server = $row['server'];
	$service = explode(', ', $row['service']);
	$port = explode(', ', $row['port']);

	$tot = count($service);

	for ($i = 0; $i < $tot; $i++) {
		$stream = fsockopen($server, $port[$i], $errono, $errostr, 30);

		if (!$stream) {
			$data[] = $service[$i];
		}
	}

	$values = $data;

	$countData = count($data);

	$values[] = implode(' e ', array_splice($values, -2));
	
	$message = 'Olá '.$adm['name'].'! O servidor: '.$server.'. Precisa de atenção. O serviço '.implode(', ', $values).' Parou de funcionar.';
	if ($countData > 1) {
		$message = 'Olá '.$adm['name'].'! O servidor: '.$server.'. Precisa de atenção. Os serviços '.implode(', ', $values).' Pararam de funcionar.';
	}
	if (isset($data)) {
		sendSms($adm['phone'], $message);
	}
}


// Função para envio de SMS
function sendSms($phone, $message) {
    global $conn;
    // Prepara o número e a mensagem
    $phone = '+55'.preg_replace('/[^0-9]/', NULL, $phone);
    $message = filter_var($message, FILTER_SANITIZE_STRING);
    if (strlen($message) > 160) {
    	print 'A mensagem suporta no máximo 160 caracteres.';
    	return FALSE;
    }

    /**
    -- Envio de SMS utilizando o serviço da Standard Library
    -- Detalhes: https://stdlib.com/@utils/lib/sms
    **/

    $url = 'https://utils.api.stdlib.com/sms@1.0.11/';
    $smsData = array('to' => $phone, 'body' => $message);
    $c = curl_init($url);

    curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($c, CURLOPT_POST, TRUE);

    // Você deve obter um token em: https://stdlib.com/@utils/lib/sms
    curl_setopt($c, CURLOPT_HTTPHEADER, array('Authorization: seu_token_aqui:123456789', 'Content-Type: application/json'));
    curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($smsData));
    $result = curl_exec($c);
}

