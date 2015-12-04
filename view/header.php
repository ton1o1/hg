<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>H&G | <?=$title;?></title>
    <meta charset="utf-8">
</head>
<body>
<h1><?=$title;?></h1>
<hr>
<?php if(!empty($_SESSION['auth'])){
	echo 'Bonjour ' . $_SESSION['auth']['firstname'] . ' ' . $_SESSION['auth']['lastname'] . ' !';
	echo '<hr>';
}
?>