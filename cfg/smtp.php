<?php
	// smtp.php
	// v1.1
	// hg/cfg

	// Conbfigure le serveur smtp
	require_once ('inc/PHPMailerAutoload.php');
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host     = 'localhost';
	$mail->SMTPAuth = false;
	$mail->Username = '';
	$mail->Password = '';
	$mail->Port     = 25;
	$mail->CharSet  = 'UTF-8';	// Pour communiquer en utf8 avec le serveur
	$mail->isHTML(true);
	$mail->setFrom('hg.admin@localhost', 'HG Admin');
