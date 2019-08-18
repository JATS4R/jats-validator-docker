<?php

header('Access-Control-Allow-Origin: *');

include_once(__DIR__ . '/../lib/doctype.php');

if ($_FILES['xml']) {
    $inputFile = $_FILES['xml']['tmp_name'];

    libxml_use_internal_errors(true);

    validateDoctypeIsSupported($inputFile);

    $doc = new \DOMDocument;

    // validate against the DTD
    $doc->load($inputFile, LIBXML_DTDLOAD | LIBXML_DTDVALID | LIBXML_NONET);

    $output = [
        'errors' => libxml_get_errors()
    ];

    header('Content-Type: application/json');
    print json_encode($output, JSON_PRETTY_PRINT);
}
