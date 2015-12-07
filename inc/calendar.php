<?php
// header('Access-Control-Allow-Methods: *');
// header('Access-Control-Allow-Credentials: true');
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Headers: Content-Type, Content-Language, Authorization');
// header('Access-Control-Expose-Headers: Authorization');

require_once 'pdo.php';
require_once '../func/datesArray.php';

// # ACTION
// $keys = isset($_REQUEST['keys'])?$_REQUEST['keys']:array();
// if (!is_array($keys)){
// 	$keys = array($keys);
// }
// $keys = array_filter($keys);

# RESULT
$unavailable = array();

	$query = $pdo->prepare("SELECT * FROM booking WHERE lodging_id = :id");
	$query->execute([
		':id' => $_GET['id']
	]);
	// Si il y a des réservations
	if($query->rowCount() > 0){
		$bookings = $query->fetchAll();

		foreach($bookings as $booking) {
			$unavailable = array_merge(datesArray($booking['check_in'], $booking['check_out']), $unavailable);
		}
	}
echo json_encode($unavailable);
exit();