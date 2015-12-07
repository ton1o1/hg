<?php

function datesArray($from, $to)
{

    $checkin = new DateTime($from); //checkin date
    $checkout = new DateTime($to); //checkout date

    $dates = array();

    while ($checkin <= $checkout) {
        $dates[] = $checkin->format('Y-m-d');
        $checkin->modify('+1 days');
    }

    return $dates;
}

?>