<?php

header('Access-Control-Allow-Origin: *');

include_once(__DIR__ . '/../../functions/dtd.php');

if ($_FILES['xml']) {
    header('Content-Type: application/json');

    $output = validate_dtd($_FILES['xml']['tmp_name']);

    print json_encode($output, JSON_PRETTY_PRINT);
}
