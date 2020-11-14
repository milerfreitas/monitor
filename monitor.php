<?php
$adm = array('name' => 'Maxmiler Freitas',
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
		$message = 'Olá '.$adm['name'].'! O servidor: '.$server.'. Precisa de atenção. Os serviços '.implode(', ', $values).' Pararam de funcionar.'.$adm['phone'];
	}
	if (isset($data)) {
		sendWhatsappMessage($adm['phone'], $message);
	}
}

// Função para envio de mensagem no WhatsApp usando o serviço do CallMeBot
function sendWhatsappMessage($phone, $message) {
	global $conn;
	if (strlen($message) > 500) {
		print 'A mensagem não pode ter mais do que 500 caracteres.';
		return false;
	}

	/**
	-- Envio de Mensagem no WhatsApp utilizando o CallMeBot
	-- Detalhes: https://www.callmebot.com/blog/free-api-whatsapp-messages/
	**/

	// Prepara o número e a mensagem
	$phone = '+55'.preg_replace('/[^0-9]/', NULL, $phone);
	$message = str_replace(' ', '+', $message);
	$message = filter_var($message, FILTER_SANITIZE_STRING);
	$apikey = '000000'; // PEGUE SUA APIKEY NO SITE: http://callmebot.com

	$ch = curl_init();
	// set url
	curl_setopt($ch, CURLOPT_URL, 'https://api.callmebot.com/whatsapp.php?phone='.$phone.'&text='.$message.'&apikey='.$apikey);

	//return the transfer as a string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// $output contains the output string
	//$output = curl_exec($ch);

	if(curl_exec($ch) === false) {
		print 'Curl error: ' . curl_error($ch);
	} else {
		print 'Operation completed without any errors';
	}

	// close curl resource to free up system resources
	curl_close($ch);
}
