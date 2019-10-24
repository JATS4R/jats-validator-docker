<?php

header('Access-Control-Allow-Origin: *');

include_once(__DIR__ . '/../../functions/format.php');

if ($_FILES['xml']) {
    header('Content-Type: application/xml');

    $output = format_xml($_FILES['xml']['tmp_name']);

    print $output;
}
