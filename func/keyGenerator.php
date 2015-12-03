<?php

function keyGenerator($params = array()){
    $defaults = array(
        'length' => 64,
        'caseSensitive' => true,
        );
    $params = array_merge($defaults, $params);

    $key = '';
    $characters = ($params['caseSensitive']) ? '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' : '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    mt_srand((double)microtime()*1000000);
    for($i=0; $i<$params['length']; $i++){
        $key .= $characters[mt_rand()%strlen($characters)];
    }

    return $key;
}

?>