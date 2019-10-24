<?php

header('Access-Control-Allow-Origin: *');

include_once(__DIR__ . '/../../functions/schematron.php');

if ($_FILES['xml'] && $_POST['schematron']) {
    header('Content-Type: application/json');

    $output = validate_schematron($_FILES['xml']['tmp_name'], $_POST['schematron']);

    print json_encode($output, JSON_PRETTY_PRINT);
}
