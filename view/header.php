<?php
// Si la session n'a pas encore été démarrée, on la démarre (évite l'affichage d'une erreur si plusieurs session_start() ont été lancés)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>H&G | <?=$title;?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/hg/css/style.css">
    <link rel="stylesheet" href="/hg/css/dateTimePicker.css">
</head>
<body style="padding:10px">
<h1><?=$title;?></h1>
<hr>
<?php if(!empty($_SESSION['auth'])){
	echo 'Bonjour ' . $_SESSION['auth']['firstname'] . ' ' . $_SESSION['auth']['lastname'] . ' !';
	echo '<hr>';
}
?>