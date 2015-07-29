<?php

    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json');

    $date = $_GET['date'];

    echo json_encode(array(
        'date' => $date,
        'fields' => [
            array(
                'id' => 'field1',
                'title' => '1. pálya',
                'subtitle' => 'Strandröplabda',
                'intervals' => [
                    array(
                        'interval' => '8:00 - 10:00',
                        'intervalBlocks' => 4, // ennyi fél órás blokkból áll
                        'reservationId' => rand(12312331, 99999999),
                        'status' => 'reservable',
                        'price' => 1250
                    ),
                    array(
                        'interval' => '10:00 - 11:00',
                        'intervalBlocks' => 2, // ennyi fél órás blokkból áll
                        'status' => 'reserved',
                        'reservedBy' => 'Kovács József',
                    ),
                    array(
                        'interval' => '11:00 - 14:00',
                        'intervalBlocks' => 6, // ennyi fél órás blokkból áll
                        'reservationId' => rand(12312331, 99999999),
                        'status' => 'reservable',
                        'price' => 1250
                    ),
                    array(
                        'interval' => '14:00 - 16:00',
                        'intervalBlocks' => 4, // ennyi fél órás blokkból áll
                        'status' => 'reserved',
                        'reservedBy' => 'Kovács József',
                    ),
                    array(
                        'interval' => '16:00 - 17:00',
                        'intervalBlocks' => 2, // ennyi fél órás blokkból áll
                        'status' => 'reserved',
                        'reservedBy' => 'Kovács József',
                    ),
                    array(
                        'interval' => '17:00 - 19:00',
                        'intervalBlocks' => 4, // ennyi fél órás blokkból áll
                        'status' => 'unavailable',
                        'message' => 'Pálya karbantartás'
                    ),
                    array(
                        'interval' => '19:00 - 22:00',
                        'intervalBlocks' => 6, // ennyi fél órás blokkból áll
                        'status' => 'unavailable',
                        'message' => 'Pálya karbantartás'
                    ),
                ]
            ),
            array(
                'id' => 'field2',
                'title' => '2. pálya',
                'subtitle' => 'Strandröplabda',
                'intervals' => [
                    array(
                        'interval' => '8:00 - 10:00',
                        'intervalBlocks' => 4, // ennyi fél órás blokkból áll
                        'reservationId' => rand(12312331, 99999999),
                        'status' => 'reservable',
                        'price' => 1250
                    ),
                    array(
                        'interval' => '10:00 - 11:00',
                        'intervalBlocks' => 2, // ennyi fél órás blokkból áll
                        'status' => 'reserved',
                        'reservedBy' => 'Kovács József',
                    ),
                    array(
                        'interval' => '11:00 - 14:00',
                        'intervalBlocks' => 6, // ennyi fél órás blokkból áll
                        'reservationId' => rand(12312331, 99999999),
                        'status' => 'reservable',
                        'price' => 1250
                    ),
                    array(
                        'interval' => '14:00 - 16:00',
                        'intervalBlocks' => 4, // ennyi fél órás blokkból áll
                        'status' => 'reserved',
                        'reservedBy' => 'Kovács József',
                    ),
                    array(
                        'interval' => '16:00 - 17:00',
                        'intervalBlocks' => 2, // ennyi fél órás blokkból áll
                        'status' => 'reserved',
                        'reservedBy' => 'Kovács József',
                    ),
                    array(
                        'interval' => '17:00 - 19:00',
                        'intervalBlocks' => 4, // ennyi fél órás blokkból áll
                        'status' => 'unavailable',
                        'message' => 'Pálya karbantartás'
                    ),
                    array(
                        'interval' => '19:00 - 22:00',
                        'intervalBlocks' => 6, // ennyi fél órás blokkból áll
                        'status' => 'unavailable',
                        'message' => 'Pálya karbantartás'
                    ),
                ]
            ),
            array(
                'id' => 'field3',
                'title' => '3. pálya',
                'subtitle' => 'Strandröplabda',
                'intervals' => [
                    array(
                        'interval' => '8:00 - 10:00',
                        'intervalBlocks' => 4, // ennyi fél órás blokkból áll
                        'reservationId' => rand(12312331, 99999999),
                        'status' => 'reservable',
                        'price' => 1250
                    ),
                    array(
                        'interval' => '10:00 - 11:00',
                        'intervalBlocks' => 2, // ennyi fél órás blokkból áll
                        'status' => 'reserved',
                        'reservedBy' => 'Kovács József',
                    ),
                    array(
                        'interval' => '11:00 - 14:00',
                        'intervalBlocks' => 6, // ennyi fél órás blokkból áll
                        'reservationId' => rand(12312331, 99999999),
                        'status' => 'reservable',
                        'price' => 1250
                    ),
                    array(
                        'interval' => '14:00 - 16:00',
                        'intervalBlocks' => 4, // ennyi fél órás blokkból áll
                        'status' => 'reserved',
                        'reservedBy' => 'Kovács József',
                    ),
                    array(
                        'interval' => '16:00 - 17:00',
                        'intervalBlocks' => 2, // ennyi fél órás blokkból áll
                        'status' => 'reserved',
                        'reservedBy' => 'Kovács József',
                    ),
                    array(
                        'interval' => '17:00 - 19:00',
                        'intervalBlocks' => 4, // ennyi fél órás blokkból áll
                        'status' => 'unavailable',
                        'message' => 'Pálya karbantartás'
                    ),
                    array(
                        'interval' => '19:00 - 22:00',
                        'intervalBlocks' => 6, // ennyi fél órás blokkból áll
                        'status' => 'unavailable',
                        'message' => 'Pálya karbantartás'
                    ),
                ]
            ),
            array(
                'id' => 'field4',
                'title' => '4. pálya',
                'subtitle' => 'Strandfoci',
                'intervals' => [
                    array(
                        'interval' => '8:00 - 10:00',
                        'intervalBlocks' => 4, // ennyi fél órás blokkból áll
                        'reservationId' => rand(12312331, 99999999),
                        'status' => 'reservable',
                        'price' => 1250
                    ),
                    array(
                        'interval' => '10:00 - 11:00',
                        'intervalBlocks' => 2, // ennyi fél órás blokkból áll
                        'status' => 'reserved',
                        'reservedBy' => 'Kovács József',
                    ),
                    array(
                        'interval' => '11:00 - 14:00',
                        'intervalBlocks' => 6, // ennyi fél órás blokkból áll
                        'reservationId' => rand(12312331, 99999999),
                        'status' => 'reservable',
                        'price' => 1250
                    ),
                    array(
                        'interval' => '14:00 - 16:00',
                        'intervalBlocks' => 4, // ennyi fél órás blokkból áll
                        'status' => 'reserved',
                        'reservedBy' => 'Kovács József',
                    ),
                    array(
                        'interval' => '16:00 - 17:00',
                        'intervalBlocks' => 2, // ennyi fél órás blokkból áll
                        'status' => 'reserved',
                        'reservedBy' => 'Kovács József',
                    ),
                    array(
                        'interval' => '17:00 - 19:00',
                        'intervalBlocks' => 4, // ennyi fél órás blokkból áll
                        'status' => 'unavailable',
                        'message' => 'Pálya karbantartás'
                    ),
                    array(
                        'interval' => '19:00 - 22:00',
                        'intervalBlocks' => 6, // ennyi fél órás blokkból áll
                        'status' => 'unavailable',
                        'message' => 'Pálya karbantartás'
                    ),
                ]
            ),
        ],
    ));

?>