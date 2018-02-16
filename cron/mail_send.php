<?php
require_once(__DIR__ .'/../vendor/autoload.php');
require_once 'config/config.php';


//definir asunto cuerpo etc

// realizar la consulta que revisa la tabla tracking y trae los registro con temporalidad multimplo de 30

//se itera dentro del result y se envian los mail, llamando a la funcion y listo


function sendEmail($to,$subject,$html,$attach_url=null){
	$from = array('test@test.com' =>'Test mail notification');
	$transport = Swift_SmtpTransport::newInstance(Email_Server, Email_Port,'tls');
	$transport->setUsername(Email_Username);
	$transport->setPassword(Email_Password);
	$swift = Swift_Mailer::newInstance($transport);

	$message = new Swift_Message($subject);
	$message->setFrom($from);
	$message->setBody($html, 'text/html');
	$message->setTo($to);
	if(!is_null($attach_url)){
		$message->attach(Swift_Attachment::fromPath($attach_url));
	}

	if ($recipients = $swift->send($message, $failures)){
		return true;
	} else {
		return false;
	}
}

?>