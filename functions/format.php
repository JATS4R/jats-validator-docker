<?php

include_once(__DIR__ . '/doctype.php');

function format_xml($inputFile) {
    libxml_use_internal_errors(true);

    validateDoctypeIsSupported($inputFile);

    $doc = new \DOMDocument;
    $doc->preserveWhiteSpace = false;
    $doc->load($inputFile, LIBXML_DTDLOAD | LIBXML_NOENT | LIBXML_NONET | LIBXML_NOXMLDECL | LIBXML_NSCLEAN);
    $doc->formatOutput = true;
    $doc->encoding = 'UTF-8';

    return $doc->saveXML();
}
