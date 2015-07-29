<?php

    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json');

    $rnd = rand(1,30);

    echo json_encode(array(
        'basketValue' => $rnd * 3200,
        'basketItems' => $rnd
    ));

?>