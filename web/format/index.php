<?php

header('Access-Control-Allow-Origin: *');

include_once(__DIR__ . '/../lib/doctype.php');

$inputFile = $_FILES['xml']['tmp_name'];

if ($inputFile) {
    libxml_use_internal_errors(true);

    validateDoctypeIsSupported($inputFile);

    $doc = new \DOMDocument;
    $doc->preserveWhiteSpace = false;
    $doc->load($inputFile, LIBXML_DTDLOAD | LIBXML_NOENT | LIBXML_NONET | LIBXML_NOXMLDECL | LIBXML_NSCLEAN);
    $doc->formatOutput = true;
    $doc->encoding = 'UTF-8';

    header('Content-Type: application/xml');
    print $doc->saveXML();
}
